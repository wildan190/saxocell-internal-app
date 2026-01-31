<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveryOrderRequest;
use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        $deliveryOrders = DeliveryOrder::with('purchaseOrder', 'supplier', 'receiver')->latest()->paginate(15);
        return view('procurement.delivery-orders.index', compact('deliveryOrders'));
    }

    public function create(Request $request)
    {
        $selectedPo = null;
        if ($request->filled('po_id')) {
            $selectedPo = PurchaseOrder::with('items.product', 'items.variant', 'supplier')
                ->whereIn('status', ['approved', 'partial'])
                ->findOrFail($request->po_id);
        }

        $purchaseOrders = PurchaseOrder::whereIn('status', ['approved', 'partial'])->with('supplier')->get();
        $warehouses = \App\Models\Warehouse::all();
        return view('procurement.delivery-orders.create', compact('purchaseOrders', 'selectedPo', 'warehouses'));
    }

    public function store(StoreDeliveryOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $po = PurchaseOrder::findOrFail($data['purchase_order_id']);
            
            // Create DO
            $do = DeliveryOrder::create([
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'delivery_date' => $data['delivery_date'],
                'received_by' => Auth::id(),
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);
            
            $allReceived = true;
            
            foreach ($data['items'] as $itemData) {
                $poItem = PurchaseOrderItem::findOrFail($itemData['purchase_order_item_id']);
                $qtyDelivered = $itemData['quantity_accepted'] + $itemData['quantity_rejected'];
                
                if ($qtyDelivered <= 0) continue;

                // Auto-create Product if Ad-hoc item
                if (!$poItem->product_id && $poItem->item_name) {
                    $generatedSku = 'GEN-' . strtoupper(uniqid());
                    $costPrice = $poItem->unit_price;
                    $suggestedPrice = $costPrice * 1.2; // 20% markup as placeholder
                    
                    $newProduct = \App\Models\Product::create([
                        'name' => $poItem->item_name,
                        'sku' => $generatedSku, 
                        'description' => ($poItem->description ?? '') . ' (Auto-created - Price needs review)',
                        'price' => $suggestedPrice,
                        'cost_price' => $costPrice,
                        'category' => $poItem->category ?? 'new',
                        'status' => 'active',
                        'stock_quantity' => 0,
                        'needs_price_review' => true,
                    ]);

                    // Link PO Item to new Product to prevent re-creation
                    $poItem->update([
                        'product_id' => $newProduct->id,
                        'product_variant_id' => null // Ensure variant is null
                    ]);
                    
                    // Refresh local instance
                    $poItem->product_id = $newProduct->id;
                }
                
                // Create DO Item
                $doItem = $do->items()->create([
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $poItem->product_id,
                    'product_variant_id' => $poItem->product_variant_id,
                    'quantity_delivered' => $qtyDelivered,
                    'quantity_accepted' => $itemData['quantity_accepted'],
                    'quantity_rejected' => $itemData['quantity_rejected'],
                    'condition_notes' => $itemData['condition_notes'] ?? null,
                    'rejection_reason' => $itemData['rejection_reason'] ?? null,
                    'resolution_type' => $itemData['quantity_rejected'] > 0 ? ($itemData['resolution_type'] ?? null) : null,
                ]);

                // Handle Rejection Resolution
                if ($itemData['quantity_rejected'] > 0 && isset($itemData['resolution_type'])) {
                    \App\Models\RejectedItem::create([
                        'delivery_order_id' => $do->id,
                        'purchase_order_item_id' => $poItem->id,
                        'quantity_rejected' => $itemData['quantity_rejected'],
                        'rejection_reason' => $itemData['rejection_reason'] ?? null,
                        'resolution_type' => $itemData['resolution_type'],
                        'replacement_received_quantity' => 0,
                    ]);
                }

                // If this delivery fulfills a prior replacement request
                $pendingReplacements = \App\Models\RejectedItem::where('purchase_order_item_id', $poItem->id)
                    ->where('resolution_type', 'replacement')
                    ->whereColumn('replacement_received_quantity', '<', 'quantity_rejected')
                    ->orderBy('created_at', 'asc')
                    ->get();

                $remainingAccepted = $itemData['quantity_accepted'];
                foreach ($pendingReplacements as $rejectedItem) {
                    if ($remainingAccepted <= 0) break;
                    
                    $needed = $rejectedItem->quantity_rejected - $rejectedItem->replacement_received_quantity;
                    $fulfilled = min($remainingAccepted, $needed);
                    
                    $rejectedItem->increment('replacement_received_quantity', $fulfilled);
                    $remainingAccepted -= $fulfilled;
                }
                
                // Update PO Item quantity_received
                $poItem->increment('quantity_received', $itemData['quantity_accepted']);
                
                // Create Inventory Transaction (Stock IN)
                if ($itemData['quantity_accepted'] > 0) {
                    InventoryTransaction::create([
                        'product_id' => $poItem->product_id,
                        'product_variant_id' => $poItem->product_variant_id,
                        'supplier_id' => $po->supplier_id,
                        'warehouse_id' => $data['warehouse_id'] ?? null,
                        'type' => 'in',
                        'quantity' => $itemData['quantity_accepted'],
                        'reference_number' => $do->do_number,
                        'notes' => "Received from PO: {$po->po_number}",
                    ]);
                    
                    // Update Warehouse Inventory (New Logic)
                    if (isset($data['warehouse_id'])) {
                        $whInv = \App\Models\WarehouseInventory::firstOrCreate([
                            'warehouse_id' => $data['warehouse_id'],
                            'product_id' => $poItem->product_id,
                            'product_variant_id' => $poItem->product_variant_id,
                        ], ['quantity' => 0]);
                        $whInv->increment('quantity', $itemData['quantity_accepted']);
                    }

                    // Update Product/Variant Stock (Global)
                    if ($poItem->product_variant_id) {
                        $poItem->variant->increment('stock_quantity', $itemData['quantity_accepted']);
                    } else {
                        $poItem->product->increment('stock_quantity', $itemData['quantity_accepted']);
                    }
                }
            }
            
            // Update PO Status using new helper
            $po->update(['status' => $po->isFullyResolved() ? 'completed' : 'partial']);
            
            DB::commit();
            return redirect()->route('delivery-orders.index')->with('success', 'Goods received and inventory updated. PO status: ' . strtoupper($po->status));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record delivery: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $do = DeliveryOrder::with('items.product', 'items.variant', 'purchaseOrder', 'supplier', 'receiver')->findOrFail($id);
        return view('procurement.delivery-orders.show', compact('do'));
    }
}

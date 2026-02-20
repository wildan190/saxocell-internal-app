<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'purchase_orders:all:' . md5(json_encode($request->all()) . request('page', 1));

        $purchaseOrders = Cache::tags(['purchase_orders'])->remember($cacheKey, 3600, function () use ($request) {
            $query = PurchaseOrder::with('supplier', 'items')->latest();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            return $query->paginate(15);
        });

        return view('procurement.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::with('variants')->where('status', 'active')->get();
        $warehouses = Warehouse::all();
        return view('procurement.purchase-orders.create', compact('suppliers', 'products', 'warehouses'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            
            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            
            foreach ($data['items'] as $item) {
                $itemSubtotal = $item['quantity_ordered'] * $item['unit_price'];
                $itemTax = $itemSubtotal * ($item['tax_rate'] ?? 0) / 100;
                $subtotal += $itemSubtotal;
                $taxAmount += $itemTax;
            }
            
            $totalAmount = $subtotal + $taxAmount;
            
            // Create PO
            $po = PurchaseOrder::create([
                'supplier_id' => $data['supplier_id'],
                'warehouse_id' => $request->warehouse_id,
                'order_date' => $data['order_date'],
                'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
                'status' => 'draft',
            ]);
            
            // Create PO items
            // Create PO items
            foreach ($data['items'] as $item) {
                // If Manual/Ad-hoc, product_id might be null or "on" (if checkbox value? no, product_id is select)
                // If checkbox is on, we ignore product_id and use item_name.
                
                $productId = null;
                $productVariantId = null;
                $itemName = null;
                
                if (isset($item['is_manual']) && $item['is_manual'] == '1') {
                    // Manual Mode
                    $itemName = $item['item_name'];
                } else {
                    // Catalog Mode
                    $productId = $item['product_id'];
                    $productVariantId = $item['product_variant_id'] ?? null;
                    // Optional: Save product name as item_name for consistency?
                    // $product = Product::find($productId);
                    // $itemName = $product->name; 
                }

                $itemSubtotal = $item['quantity_ordered'] * $item['unit_price'];

                $po->items()->create([
                    'product_id' => $productId,
                    'product_variant_id' => $productVariantId,
                    'item_name' => $itemName,
                    'description' => $item['description'] ?? null,
                    'category' => $item['category'] ?? 'new',
                    'quantity_ordered' => $item['quantity_ordered'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'subtotal' => $itemSubtotal,
                ]);
            }
            
            DB::commit();
            Cache::tags(['purchase_orders'])->flush();
            return redirect()->route('purchase-orders.show', $po)->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create Purchase Order: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $po = Cache::tags(['purchase_orders'])->remember("purchase_orders:show:{$id}", 3600, function () use ($id) {
            return PurchaseOrder::with('supplier', 'items.product', 'items.variant', 'creator', 'approver')->findOrFail($id);
        });
        return view('procurement.purchase-orders.show', compact('po'));
    }

    public function approve($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if ($po->status !== 'draft' && $po->status !== 'submitted') {
            return back()->with('error', 'Only draft or submitted POs can be approved.');
        }
        
        $po->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        Cache::tags(['purchase_orders'])->flush();
        
        return back()->with('success', 'Purchase Order approved successfully.');
    }

    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        
        if ($po->status === 'approved' || $po->status === 'completed') {
            return back()->with('error', 'Cannot delete approved or completed POs.');
        }
        
        $po->delete();
        Cache::tags(['purchase_orders'])->flush();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order deleted.');
    }

    public function downloadPdf($id)
    {
        $po = PurchaseOrder::with('supplier', 'items.product', 'items.variant', 'creator', 'approver')->findOrFail($id);
        
        $pdf = Pdf::loadView('procurement.purchase-orders.pdf', compact('po'));
        
        // Set paper to A4 and orientation to portrait
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download("PO-{$po->po_number}.pdf");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Warehouse;
use App\Models\Store;
use App\Models\Product;
use App\Models\WarehouseInventory;
use App\Models\StoreInventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['sourceWarehouse', 'destinationStore'])->latest()->get();
        
        $stats = [
            'total' => $transfers->count(),
            'pending' => $transfers->where('status', 'pending')->count(),
            'requested' => $transfers->where('status', 'requested')->count(),
            'received' => $transfers->where('status', 'received')->count(),
        ];

        return view('stock-transfers.index', compact('transfers', 'stats'));
    }

    public function create(Request $request)
    {
        $warehouses = Warehouse::all();
        $sourceWarehouseId = $request->query('source_warehouse_id');
        
        $inventory = [];
        $stores = Store::all();

        if ($sourceWarehouseId) {
            $inventory = WarehouseInventory::where('warehouse_id', $sourceWarehouseId)
                ->where('quantity', '>', 0)
                ->with(['product', 'productVariant', 'product.variants'])
                ->get();
        }

        return view('stock-transfers.create', compact('warehouses', 'inventory', 'stores', 'sourceWarehouseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_store_id' => 'required|exists:stores,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // Generate Reference Number
                $refNumber = 'TRF-' . date('Ymd') . '-' . strtoupper(uniqid());

                $transfer = StockTransfer::create([
                    'source_warehouse_id' => $validated['source_warehouse_id'],
                    'destination_store_id' => $validated['destination_store_id'],
                    'status' => 'pending',
                    'reference_number' => $refNumber,
                ]);

                foreach ($validated['items'] as $item) {
                    $whInventory = WarehouseInventory::where('warehouse_id', $validated['source_warehouse_id'])
                        ->where('product_id', $item['product_id'])
                        ->where('product_variant_id', $item['product_variant_id'] ?? null)
                        ->first();
                    
                    if (!$whInventory || $whInventory->quantity < $item['quantity']) {
                        throw new \Exception('Insufficient stock for product. Please check availability.');
                    }

                    StockTransferItem::create([
                        'stock_transfer_id' => $transfer->id,
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['product_variant_id'] ?? null,
                        'quantity_sent' => $item['quantity'],
                        'quantity_received' => 0,
                    ]);

                    // Deduct from Warehouse immediately (In Transit state)
                    $whInventory->decrement('quantity', $item['quantity']);
                    
                    // Decrement Global Stock (temporarily until received)
                    if ($item['product_variant_id'] ?? null) {
                        $whInventory->productVariant->decrement('stock_quantity', $item['quantity']);
                    } else {
                        $whInventory->product->decrement('stock_quantity', $item['quantity']);
                    }

                    // Record Out Transaction
                    InventoryTransaction::create([
                        'warehouse_id' => $validated['source_warehouse_id'],
                        'product_id' => $item['product_id'],
                        'product_variant_id' => $item['product_variant_id'] ?? null,
                        'stock_transfer_id' => $transfer->id,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'reference_number' => $refNumber,
                        'notes' => 'Transfer Out to Store',
                    ]);
                }
            });

            return redirect()->route('stock-transfers.index')
                ->with('success', 'Stock Transfer request created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Transfer failed: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $transfer = StockTransfer::with(['items.product', 'items.productVariant', 'sourceWarehouse', 'destinationStore'])->findOrFail($id);
        return view('stock-transfers.show', compact('transfer'));
    }

    public function receive(Request $request, $id)
    {
        $transfer = StockTransfer::findOrFail($id);

        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Transfer already processed.');
        }

        $items = $request->input('items', []); // keyed by item id, value = quantity_received

        DB::transaction(function () use ($transfer, $items) {
            foreach ($items as $itemId => $qtyReceived) {
                $transferItem = StockTransferItem::where('stock_transfer_id', $transfer->id)
                    ->where('id', $itemId) // Use ID to be safe
                    ->firstOrFail();

                $transferItem->quantity_received = $qtyReceived;
                $transferItem->save();

                if ($qtyReceived > 0) {
                    // Add to Store Inventory (Variant-Aware)
                    $storeInv = StoreInventory::firstOrCreate([
                        'store_id' => $transfer->destination_store_id,
                        'product_id' => $transferItem->product_id,
                        'product_variant_id' => $transferItem->product_variant_id,
                    ], ['quantity' => 0]);
                    
                    $storeInv->increment('quantity', $qtyReceived);

                    // Record In Transaction
                    InventoryTransaction::create([
                        'store_id' => $transfer->destination_store_id,
                        'product_id' => $transferItem->product_id,
                        'product_variant_id' => $transferItem->product_variant_id,
                        'stock_transfer_id' => $transfer->id,
                        'type' => 'in',
                        'quantity' => $qtyReceived,
                        'reference_number' => $transfer->reference_number,
                        'notes' => 'Transfer In from Warehouse',
                    ]);
                    
                    // Update Product/Variant Total Stock (Global)
                    if ($transferItem->product_variant_id) {
                        $transferItem->productVariant->increment('stock_quantity', $qtyReceived);
                    } else {
                        $transferItem->product->increment('stock_quantity', $qtyReceived);
                    }
                }
            }
            
            $transfer->update(['status' => 'received']);
        });

        return redirect()->route('stock-transfers.show', $transfer->id)
            ->with('success', 'Stock Transfer received successfully.');
    }
    public function createRequest()
    {
        $stores = Store::all();
        $warehouses = Warehouse::all();
        $products = Product::with('variants')->orderBy('name')->get();
        return view('stock-transfers.create_request', compact('stores', 'warehouses', 'products'));
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_store_id' => 'required|exists:stores,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $refNumber = 'REQ-' . date('Ymd') . '-' . strtoupper(uniqid());

            $transfer = StockTransfer::create([
                'source_warehouse_id' => $validated['source_warehouse_id'],
                'destination_store_id' => $validated['destination_store_id'],
                'status' => 'requested',
                'reference_number' => $refNumber,
            ]);

            foreach ($validated['items'] as $item) {
                StockTransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity_sent' => $item['quantity'],
                    'quantity_received' => 0,
                ]);
            }
        });

        return redirect()->route('stock-transfers.index')
            ->with('success', 'Stock Request created successfully. Waiting for Warehouse approval.');
    }

    public function approve(Request $request, $id)
    {
        Log::info('Approving Stock Transfer', ['id' => $id]);
        
        $transfer = StockTransfer::with('items')->findOrFail($id);
        
        Log::info('Transfer Status', ['status' => $transfer->status]);

        if ($transfer->status !== 'requested') {
            Log::warning('Approve failed: Status not requested', ['status' => $transfer->status]);
            return back()->with('error', 'Only requested transfers can be approved.');
        }

        try {
            DB::transaction(function () use ($transfer) {
                Log::info('Starting Transaction for Transfer ' . $transfer->id);
                
                foreach ($transfer->items as $item) {
                    Log::info('Processing Item', ['item_id' => $item->id, 'product_id' => $item->product_id, 'qty' => $item->quantity_sent]);
                    
                    $whInventory = WarehouseInventory::where('warehouse_id', $transfer->source_warehouse_id)
                        ->where('product_id', $item->product_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();
                    
                    if (!$whInventory) {
                        Log::error('Inventory not found', ['wh_id' => $transfer->source_warehouse_id, 'prod_id' => $item->product_id, 'variant_id' => $item->product_variant_id]);
                        throw new \Exception('Inventory record not found for requested item.');
                    }
                    
                    Log::info('Inventory Found', ['current_qty' => $whInventory->quantity]);

                    if ($whInventory->quantity < $item->quantity_sent) {
                        Log::error('Insufficient stock', ['avail' => $whInventory->quantity, 'req' => $item->quantity_sent]);
                        throw new \Exception("Insufficient stock in warehouse for " . ($item->productVariant ? $item->productVariant->name : $item->product->name));
                    }

                    // Deduct from Warehouse
                    $whInventory->decrement('quantity', $item->quantity_sent);
                    
                    // Decrement Global Stock
                    if ($item->product_variant_id) {
                        $item->productVariant->decrement('stock_quantity', $item->quantity_sent);
                    } else {
                        $item->product->decrement('stock_quantity', $item->quantity_sent);
                    }

                    // Record Out Transaction
                    InventoryTransaction::create([
                        'warehouse_id' => $transfer->source_warehouse_id,
                        'product_id' => $item->product_id,
                        'product_variant_id' => $item->product_variant_id,
                        'stock_transfer_id' => $transfer->id,
                        'type' => 'out',
                        'quantity' => $item->quantity_sent,
                        'reference_number' => $transfer->reference_number,
                        'notes' => 'Stock Request Approved (Out)',
                    ]);
                }

                $transfer->update(['status' => 'pending']);
                \Illuminate\Support\Facades\Log::info('Transfer updated to pending');
            });

            return redirect()->route('stock-transfers.show', $transfer->id)
                ->with('success', 'Request approved and stock deducted.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Approve Exception', ['msg' => $e->getMessage()]);
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $transfer = StockTransfer::findOrFail($id);
        if ($transfer->status !== 'requested') {
            return back()->with('error', 'Only requested transfers can be rejected.');
        }

        $transfer->update(['status' => 'cancelled']);
        return back()->with('success', 'Stock Request rejected/cancelled.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\InventoryTransaction;
use App\Models\WarehouseInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with('warehouse')->latest()->get();
        return view('stock-opnames.index', compact('opnames'));
    }

    public function create(Request $request)
    {
        $warehouses = Warehouse::all();
        $selectedWarehouseId = $request->query('warehouse_id');
        return view('stock-opnames.create', compact('warehouses', 'selectedWarehouseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $opname = StockOpname::create([
                'warehouse_id' => $validated['warehouse_id'],
                'date' => $validated['date'],
                'status' => 'in_progress',
                'notes' => $validated['notes'],
            ]);

            // Snapshot current inventory
            $products = Product::with('variants')->get();
            
            // Determine system qty for each product/variant in this warehouse
            $inventory = WarehouseInventory::where('warehouse_id', $validated['warehouse_id'])
                ->get()
                ->groupBy('product_id');

            foreach ($products as $product) {
                if ($product->variants->isNotEmpty()) {
                    foreach ($product->variants as $variant) {
                        $variantInventory = $inventory->get($product->id)?->where('product_variant_id', $variant->id)->first();
                        
                        StockOpnameItem::create([
                            'stock_opname_id' => $opname->id,
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'system_qty' => $variantInventory->quantity ?? 0,
                            'actual_qty' => null,
                            'difference' => null,
                        ]);
                    }
                } else {
                    $baseInventory = $inventory->get($product->id)?->where('product_variant_id', null)->first();
                    
                    StockOpnameItem::create([
                        'stock_opname_id' => $opname->id,
                        'product_id' => $product->id,
                        'product_variant_id' => null,
                        'system_qty' => $baseInventory->quantity ?? 0,
                        'actual_qty' => null,
                        'difference' => null,
                    ]);
                }
            }
        });

        return redirect()->route('stock-opnames.index')
            ->with('success', 'Stock Opname session created. Ready to input counts.');
    }

    public function show(StockOpname $id)
    {
        $opname = $id; 
        $opname->load(['warehouse', 'items.product', 'items.productVariant']);
        return view('stock-opnames.show', compact('opname'));
    }

    public function finalize(Request $request, $id)
    {
        $opname = StockOpname::findOrFail($id);
        
        if ($opname->status !== 'in_progress') {
            return back()->with('error', 'Opname is already finalized.');
        }

        $items = $request->input('items', []);

        DB::transaction(function () use ($opname, $items) {
            foreach ($items as $itemId => $data) {
                $item = StockOpnameItem::findOrFail($itemId);
                
                if ($item->stock_opname_id !== $opname->id) continue;

                $actualQty = $data['actual_qty'];
                if ($actualQty === null || $actualQty === '') continue; // Skip if not counted? Or assume 0?
                // For opname, if it's in the list, we should probably have a value.
                
                $item->actual_qty = $actualQty;
                $item->difference = $actualQty - $item->system_qty;
                $item->save();

                if ($item->difference != 0) {
                    $this->createAdjustment($opname, $item);
                }
            }

            $opname->update(['status' => 'completed']);
        });

        return redirect()->route('stock-opnames.show', $opname)
            ->with('success', 'Stock Opname finalized and inventory updated.');
    }

    private function createAdjustment(StockOpname $opname, StockOpnameItem $item)
    {
        // Create Inventory Transaction
        InventoryTransaction::create([
            'warehouse_id' => $opname->warehouse_id,
            'product_id' => $item->product_id,
            'product_variant_id' => $item->product_variant_id,
            'stock_opname_id' => $opname->id,
            'type' => 'adjustment',
            'quantity' => $item->difference,
            'reference_number' => 'OPNAME-' . $opname->id,
            'notes' => 'Stock Opname Adjustment',
        ]);

        // Update Warehouse Inventory
        $inventory = WarehouseInventory::updateOrCreate(
            [
                'warehouse_id' => $opname->warehouse_id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
            ],
            [
                'quantity' => $item->actual_qty,
            ]
        );

        // Update Product or Variant Total Stock
        if ($item->product_variant_id) {
            $variant = $item->productVariant;
            $variant->stock_quantity += $item->difference;
            $variant->save();
            
            // Product total stock is an accessor or needs update too?
            // If product has variants, stock_quantity on product table might be unused or aggregate.
            // Earlier implementation said: "Product::total_stock is an accessor"
            // But let's see Product model again.
        } else {
            $product = $item->product;
            $product->stock_quantity += $item->difference;
            $product->save();
        }
    }
}

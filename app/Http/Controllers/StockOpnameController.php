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
            $products = Product::all(); // Should filter active?
            // Determine system qty for each product in this warehouse
            $inventory = WarehouseInventory::where('warehouse_id', $validated['warehouse_id'])
                ->pluck('quantity', 'product_id');

            foreach ($products as $product) {
                StockOpnameItem::create([
                    'stock_opname_id' => $opname->id,
                    'product_id' => $product->id,
                    'system_qty' => $inventory[$product->id] ?? 0,
                    'actual_qty' => null, // To be filled
                    'difference' => null,
                ]);
            }
        });

        return redirect()->route('stock-opnames.index')
            ->with('success', 'Stock Opname session created. ready to input counts.');
    }

    public function show(StockOpname $id)
    {
        // $id is implicitly bound but naming it $opname would be better, using $id to match route param if needed or relying on Laravel binding
        // Route is resource-like but defined manually as get /stock-opnames/{id}
        // Let's assume standard binding work if I typehint
        $opname = $id; 
        $opname->load(['warehouse', 'items.product']);
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
                
                // Validate that item belongs to this opname
                if ($item->stock_opname_id !== $opname->id) continue;

                $actualQty = $data['actual_qty'] ?? 0; // Default to 0? Or require input?
                // Let's assume 0 if empty, strictly speaking opname means counting everything.
                
                $item->actual_qty = $actualQty;
                $item->difference = $actualQty - $item->system_qty;
                $item->save();

                // If difference exists, create adjustment
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
            'stock_opname_id' => $opname->id,
            'type' => 'adjustment',
            'quantity' => abs($item->difference), // Transaction tracks absolute magnitude? 
            // Wait, InventoryTransaction usually has 'type' (in/out/adjustment).
            // Adjustment logic: if diff is +5 (found 5 more), we need to add 5.
            // If diff is -5 (lost 5), we need to remove 5.
            // My model logic: getSignedQuantityAttribute() logic:
            // 'adjustment' => $this->quantity.
            // So if I set quantity to -5, signed is -5.
            // So I should store the signed difference directly? 
            // Or store absolute and use another field?
            // The table schema has 'quantity' as integer.
            // The model `getSignedQuantityAttribute` says:
            // 'adjustment' => $this->quantity.
			// So I should store negative value if negative adjustment.
            'quantity' => $item->difference,
            'reference_number' => 'OPNAME-' . $opname->id,
            'notes' => 'Stock Opname Adjustment',
        ]);

        // Update Warehouse Inventory
        $inventory = WarehouseInventory::firstOrNew([
            'warehouse_id' => $opname->warehouse_id,
            'product_id' => $item->product_id,
        ]);
        $inventory->quantity = $item->actual_qty; // Directly set to actual? Or add difference?
        // Set to actual is safer/more correct for opname.
        $inventory->save();

        // Update Product Total Stock (optional, if cached)
        // Ignoring for now or relying on accessors.
        // Actually Product::total_stock is an accessor so it calculates on fly if hasVariants.
        // But if `stock_quantity` column exists on products table, it needs sync?
        // Implementation plan said: "I will maintain stock_quantity on products as a "Global Total"".
        // So yes, I should update it.
        $product = $item->product;
        // Re-calculate total from all warehouses? Or just adjust?
        // Adjust is faster.
        $product->stock_quantity += $item->difference;
        $product->save();
    }
}

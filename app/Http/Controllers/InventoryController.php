<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Models\Product;
use App\Models\Supplier;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected $inventoryRepository;

    public function __construct(InventoryRepositoryInterface $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function index(Request $request)
    {
        $transactions = $this->inventoryRepository->getAll($request->all());
        $products = Product::select('id', 'name')->get();

        return view('inventory.index', compact('transactions', 'products'));
    }

    public function create()
    {
        $products = Product::with('variants')->where('status', 'active')->get();
        $suppliers = Supplier::all();
        $warehouses = \App\Models\Warehouse::all();
        
        return view('inventory.create', compact('products', 'suppliers', 'warehouses'));
    }

    public function store(StoreInventoryTransactionRequest $request)
    {
        $this->inventoryRepository->create($request->validated());
        return redirect()->route('inventory.index')->with('success', 'Inventory transaction recorded successfully.');
    }
}

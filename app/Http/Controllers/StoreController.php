<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Account;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function storeAccount(Request $request, Store $store)
    {
        $validated = $request->validate([
            'code' => 'required|unique:accounts,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'category' => 'nullable',
        ]);

        $store->accounts()->create($validated);

        return back()->with('success', 'Account created and linked to store successfully.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::latest()->get();
        return view('stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Store::create($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        $store->load(['inventory.product', 'accounts']);
        
        $stats = [
            'total_skus' => $store->inventory->count(),
            'active_products' => $store->inventory->where('is_active', true)->count(),
            'low_stock' => $store->inventory->where('quantity', '<=', 5)->count(),
            'out_of_stock' => $store->inventory->where('quantity', '<=', 0)->count(),
        ];

        $pendingOrdersCount = $store->orders()
            ->whereIn('status', ['pending_payment', 'pending_confirmation'])
            ->count();

        return view('stores.show', compact('store', 'stats', 'pendingOrdersCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $store->update($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }

    public function toggleInventoryStatus(Request $request, Store $store, $inventoryId)
    {
        // Fetch valid inventory item belonging to this store
        $inventory = \App\Models\StoreInventory::where('store_id', $store->id)
            ->where('id', $inventoryId)
            ->firstOrFail();

        // Toggle status
        $inventory->is_active = !$inventory->is_active;
        $inventory->save();

        $status = $inventory->is_active ? 'active' : 'inactive';
        
        return back()->with('success', "Product status updated to {$status}.");
    }
}

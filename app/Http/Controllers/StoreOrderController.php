<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\StoreInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreOrderController extends Controller
{
    public function index(Store $store)
    {
        $orders = $store->orders()->latest()->paginate(20);
        return view('stores.orders.index', compact('store', 'orders'));
    }

    public function show(Store $store, Order $order)
    {
        if ($order->store_id !== $store->id) abort(404);
        return view('stores.orders.show', compact('store', 'order'));
    }

    public function confirm(Store $store, Order $order)
    {
        if ($order->store_id !== $store->id) abort(404);

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Order confirmed and processing.');
    }

    public function reject(Store $store, Order $order)
    {
        if ($order->store_id !== $store->id) abort(404);

        try {
            DB::beginTransaction();

            // Restock items
            foreach ($order->items as $item) {
                $inventory = StoreInventory::where('store_id', $store->id)
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($inventory) {
                    $inventory->quantity += $item->quantity;
                    $inventory->save();
                }
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();
            return back()->with('success', 'Order rejected and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject order: ' . $e->getMessage());
        }
    }
}

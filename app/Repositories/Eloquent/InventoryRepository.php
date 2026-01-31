<?php

namespace App\Repositories\Eloquent;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $cacheKey = 'inventory:all:' . md5(json_encode($filters) . request('page', 1));

        return Cache::tags(['inventory'])->remember($cacheKey, 3600, function () use ($filters) {
            $query = InventoryTransaction::with(['product', 'productVariant', 'supplier'])
                ->latest();

            // Filter by type
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            // Filter by product
            if (!empty($filters['product_id'])) {
                $query->where('product_id', $filters['product_id']);
            }

            return $query->paginate(20);
        });
    }

    public function create(array $data)
    {
        $transaction = InventoryTransaction::create($data);
        
        // Update stock based on transaction type
        $this->updateStock($transaction);
        
        Cache::tags(['inventory', 'products'])->flush();
        
        return $transaction;
    }

    protected function updateStock(InventoryTransaction $transaction)
    {
        $quantity = $transaction->signed_quantity;

        // 1. Update global product/variant stock
        if ($transaction->product_variant_id) {
            $variant = $transaction->productVariant;
            $variant->stock_quantity += $quantity;
            $variant->save();
        } else {
            $product = $transaction->product;
            $product->stock_quantity += $quantity;
            $product->save();
        }

        // 2. Update Warehouse inventory if warehouse_id is present
        if ($transaction->warehouse_id) {
            $inventory = \App\Models\WarehouseInventory::firstOrNew([
                'warehouse_id' => $transaction->warehouse_id,
                'product_id' => $transaction->product_id,
                'product_variant_id' => $transaction->product_variant_id,
            ]);
            $inventory->quantity += $quantity;
            $inventory->save();
        }

        // 3. Update Store inventory if store_id is present
        if ($transaction->store_id) {
            $inventory = \App\Models\StoreInventory::firstOrNew([
                'store_id' => $transaction->store_id,
                'product_id' => $transaction->product_id,
                'product_variant_id' => $transaction->product_variant_id,
            ]);
            $inventory->quantity += $quantity;
            $inventory->save();
        }
    }
}

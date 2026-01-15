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
        if ($transaction->product_variant_id) {
            // Update variant stock
            $variant = $transaction->productVariant;
            $variant->stock_quantity += $transaction->signed_quantity;
            $variant->save();
        } else {
            // Update product stock
            $product = $transaction->product;
            $product->stock_quantity += $transaction->signed_quantity;
            $product->save();
        }
    }
}

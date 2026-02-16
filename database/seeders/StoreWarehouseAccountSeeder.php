<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\Account;

class StoreWarehouseAccountSeeder extends Seeder
{
    public function run()
    {
        $stores = Store::all();
        foreach ($stores as $store) {
            $this->createAccounts($store, 'Store');
        }

        $warehouses = Warehouse::all();
        foreach ($warehouses as $warehouse) {
            $this->createAccounts($warehouse, 'Warehouse');
        }
    }

    private function createAccounts($model, $prefix)
    {
        // Cash Account
        if ($model->accounts()->where('name', 'like', '%Cash%')->doesntExist()) {
            $model->accounts()->create([
                'code' => $this->generateCode($prefix, $model->name, 'CASH'),
                'name' => "{$prefix} {$model->name} Cash",
                'type' => 'asset',
                'category' => 'current_asset',
                'is_active' => true,
                'current_balance' => 0,
            ]);
        }

        // Bank Account (Generic Placeholder)
        if ($model->accounts()->where('name', 'like', '%Bank%')->doesntExist()) {
            $model->accounts()->create([
                'code' => $this->generateCode($prefix, $model->name, 'BANK'),
                'name' => "{$prefix} {$model->name} Bank",
                'type' => 'asset',
                'category' => 'current_asset',
                'is_active' => true,
                'current_balance' => 0,
            ]);
        }
    }

    private function generateCode($prefix, $name, $type)
    {
        // Simple code generation logic
        return strtoupper(substr($prefix, 0, 1) . '-' . substr($name, 0, 3) . '-' . $type . '-' . rand(100, 999));
    }
}

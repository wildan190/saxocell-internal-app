<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function accounts(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Account::class, 'owner');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }
}

<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasUuids, LogsActivity;

    protected $fillable = [
        'name',
        'address',
        'description',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = \Illuminate\Support\Str::slug($store->name);
            }
        });
        
        static::updating(function ($store) {
             if (empty($store->slug)) {
                $store->slug = \Illuminate\Support\Str::slug($store->name);
            }
        });
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    public function accounts(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Account::class, 'owner');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

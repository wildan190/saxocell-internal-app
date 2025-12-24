<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'stock_quantity',
        'attributes',
        'image',
        'is_default',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    /**
     * Get the product that owns this variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the effective price (variant price or fallback to product price).
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    /**
     * Check if variant is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if variant is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Get formatted attributes for display.
     */
    public function getFormattedAttributesAttribute(): string
    {
        $attributes = $this->getAttribute('attributes');

        if (!$attributes) {
            return '';
        }

        return collect($attributes)
            ->map(fn($value, $key) => ucfirst($key) . ': ' . $value)
            ->join(', ');
    }

    /**
     * Get all inventory transactions for this variant.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\InventoryTransaction::class);
    }
}

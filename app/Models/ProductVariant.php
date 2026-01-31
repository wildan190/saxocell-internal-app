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

    protected $appends = [
        'formatted_attributes',
        'qr_code_content'
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
     * Get formatted attributes as an array for iteration.
     */
    public function getFormattedAttributesAttribute(): array
    {
        $attributes = $this->getAttribute('attributes');

        if (!$attributes) {
            return [];
        }

        return collect($attributes)
            ->mapWithKeys(fn($value, $key) => [ucfirst($key) => $value])
            ->toArray();
    }

    /**
     * Get attributes as a joined string for simple display.
     */
    public function getAttributesSummaryAttribute(): string
    {
        $formatted = $this->formatted_attributes;

        if (empty($formatted)) {
            return '';
        }

        return collect($formatted)
            ->map(fn($value, $key) => "$key: $value")
            ->join(', ');
    }

    /**
     * Get all inventory transactions for this variant.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\InventoryTransaction::class);
    }

    /**
     * Get the content for QR code.
     */
    public function getQrCodeContentAttribute(): string
    {
        return $this->sku ?? $this->product->sku ?? "PROD-{$this->product_id}-VAR-{$this->id}";
    }
}

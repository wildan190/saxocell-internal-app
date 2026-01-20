<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'cost_price',
        'category',
        'status',
        'product_specs',
        'sku',
        'stock_quantity',
        'image',
        'needs_price_review',
    ];

    protected $casts = [
        'product_specs' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get all variants for this product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get active variants only.
     */
    public function activeVariants(): HasMany
    {
        return $this->variants()->where('status', 'active');
    }

    /**
     * Get the default variant.
     */
    public function defaultVariant()
    {
        return $this->variants()->where('is_default', true)->first()
            ?? $this->variants()->first();
    }

    /**
     * Check if product has variants.
     */
    public function hasVariants(): bool
    {
        return $this->variants()->exists();
    }

    /**
     * Get total stock quantity across all variants or product stock.
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->hasVariants()) {
            return $this->activeVariants()->sum('stock_quantity');
        }

        return $this->stock_quantity;
    }

    /**
     * Get effective price (from default variant or product price).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->hasVariants()) {
            $defaultVariant = $this->defaultVariant();
            return $defaultVariant ? $defaultVariant->effective_price : $this->price;
        }

        return $this->price;
    }

    /**
     * Generate SKU for product if not exists.
     */
    public function generateSku(): string
    {
        if ($this->sku) {
            return $this->sku;
        }

        // Generate SKU: PREFIX + PRODUCT_ID + RANDOM_SUFFIX
        $prefix = strtoupper(substr($this->category, 0, 2)); // NE for new, US for used
        $productId = str_pad($this->id, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(Str::random(3));

        return $prefix . $productId . $random;
    }

    /**
     * Generate SKU for variant.
     */
    public function generateVariantSku(ProductVariant $variant): string
    {
        if ($variant->sku) {
            return $variant->sku;
        }

        // Generate variant SKU: PRODUCT_SKU + VARIANT_SUFFIX
        $baseSku = $this->generateSku();
        $attributes = $variant->getAttribute('attributes') ?? [];

        // Create suffix from attributes using hash to guarantee uniqueness
        $attrString = collect($attributes)
            ->map(fn($v, $k) => "$k:$v")
            ->sort()
            ->join('|');
            
        $hash = substr(strtoupper(md5($attrString)), 0, 6);

        return $baseSku . '-' . $hash;
    }

    /**
     * Check if product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * Get price range for products with variants.
     */
    public function getPriceRangeAttribute(): ?array
    {
        if (!$this->hasVariants()) {
            return null;
        }

        $prices = $this->activeVariants()
            ->whereNotNull('price')
            ->pluck('price')
            ->sort()
            ->values();

        if ($prices->isEmpty()) {
            return null;
        }

        return [
            'min' => $prices->first(),
            'max' => $prices->last(),
        ];
    }

    /**
     * Get all inventory transactions for this product.
     */
    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(\App\Models\InventoryTransaction::class);
    }

    public function warehouseInventory(): HasMany
    {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function storeInventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class);
    }

    /**
     * Scope to get products that need price review.
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('needs_price_review', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'supplier_id',
        'warehouse_id',
        'store_id',
        'stock_transfer_id',
        'stock_opname_id',
        'type',
        'quantity',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the product for this transaction.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant for this transaction.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the supplier for this transaction.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function opname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    /**
     * Get the signed quantity based on transaction type.
     */
    public function getSignedQuantityAttribute(): int
    {
        return match($this->type) {
            'in' => $this->quantity,
            'out' => -$this->quantity,
            'adjustment' => $this->quantity, // Can be positive or negative
            default => 0,
        };
    }
}

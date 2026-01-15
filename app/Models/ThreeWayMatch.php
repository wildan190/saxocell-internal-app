<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThreeWayMatch extends Model
{
    use HasUuids;

    protected $fillable = [
        'purchase_order_id',
        'delivery_order_id',
        'invoice_id',
        'status',
        'quantity_match',
        'price_match',
        'total_match',
        'discrepancy_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'quantity_match' => 'boolean',
        'price_match' => 'boolean',
        'total_match' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'resolved_by');
    }

    public function getIsFullyMatchedAttribute(): bool
    {
        return $this->quantity_match && $this->price_match && $this->total_match;
    }
}

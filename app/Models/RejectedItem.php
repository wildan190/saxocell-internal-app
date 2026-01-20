<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RejectedItem extends Model
{
    protected $fillable = [
        'delivery_order_id',
        'purchase_order_item_id',
        'quantity_rejected',
        'rejection_reason',
        'resolution_type',
        'replacement_received_quantity',
    ];

    /**
     * Get the delivery order that this rejection belongs to.
     */
    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    /**
     * Get the purchase order item associated with this rejection.
     */
    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    /**
     * Check if the rejection is fully resolved.
     */
    public function isResolved(): bool
    {
        if ($this->resolution_type === 'refund') {
            return true;
        }

        if ($this->resolution_type === 'replacement') {
            return $this->replacement_received_quantity >= $this->quantity_rejected;
        }

        return false;
    }

    /**
     * Get remaining quantity to be replaced.
     */
    public function getRemainingReplacementQuantity(): int
    {
        if ($this->resolution_type !== 'replacement') {
            return 0;
        }

        return max(0, $this->quantity_rejected - $this->replacement_received_quantity);
    }
}

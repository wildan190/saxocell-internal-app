<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryOrderItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'delivery_order_id',
        'purchase_order_item_id',
        'product_id',
        'product_variant_id',
        'quantity_delivered',
        'quantity_accepted',
        'quantity_rejected',
        'condition_notes',
    ];

    protected $casts = [
        'quantity_delivered' => 'integer',
        'quantity_accepted' => 'integer',
        'quantity_rejected' => 'integer',
    ];

    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}

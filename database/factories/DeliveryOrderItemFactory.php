<?php

namespace Database\Factories;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryOrderItemFactory extends Factory
{
    protected $model = DeliveryOrderItem::class;

    public function definition(): array
    {
        $poItem = PurchaseOrderItem::factory()->create();
        
        return [
            'delivery_order_id' => DeliveryOrder::factory(),
            'purchase_order_item_id' => $poItem->id,
            'product_id' => $poItem->product_id,
            'product_variant_id' => $poItem->product_variant_id,
            'quantity_delivered' => $poItem->quantity_ordered,
            'quantity_accepted' => $poItem->quantity_ordered,
            'quantity_rejected' => 0,
            'condition_notes' => 'Good condition',
        ];
    }
}

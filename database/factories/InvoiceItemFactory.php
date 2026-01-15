<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $poItem = PurchaseOrderItem::factory()->create();

        return [
            'invoice_id' => Invoice::factory(),
            'purchase_order_item_id' => $poItem->id,
            'product_id' => $poItem->product_id,
            'product_variant_id' => $poItem->product_variant_id,
            'quantity' => $poItem->quantity_ordered,
            'unit_price' => $poItem->unit_price,
            'tax_rate' => $poItem->tax_rate,
            'subtotal' => $poItem->subtotal,
        ];
    }
}

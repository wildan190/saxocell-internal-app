<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $variant = $product->variants()->first();

        $quantity = $this->faker->numberBetween(1, 100);
        $unitPrice = $variant ? $variant->price : $product->price;
        $taxRate = 11.00; // Standard VAT Indonesia
        
        $subtotal = $quantity * $unitPrice;
        $taxAmount = ($subtotal * $taxRate) / 100;

        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => $product->id,
            'product_variant_id' => $variant ? $variant->id : null,
            'quantity_ordered' => $quantity,
            'quantity_received' => 0,
            'unit_price' => $unitPrice,
            'tax_rate' => $taxRate,
            'subtotal' => $subtotal + $taxAmount, // Total for this item
        ];
    }
}

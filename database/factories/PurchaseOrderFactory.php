<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'po_number' => 'PO-' . $this->faker->unique()->numerify('######'),
            'supplier_id' => Supplier::factory(),
            'status' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'partial', 'completed', 'cancelled']),
            'order_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'expected_delivery_date' => $this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'subtotal' => 0, // Will be calculated based on items
            'tax_amount' => 0,
            'shipping_cost' => $this->faker->randomFloat(2, 50000, 500000),
            'total_amount' => 0,
            'notes' => $this->faker->sentence(),
            'created_by' => User::factory(),
            'approved_by' => function (array $attributes) {
                return in_array($attributes['status'], ['approved', 'partial', 'completed']) ? User::factory() : null;
            },
            'approved_at' => function (array $attributes) {
                return $attributes['approved_by'] ? now() : null;
            },
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (PurchaseOrder $po) {
            // PurchaseOrderItems will populate subtotal/tax/total if created via PO::factory()->hasItems()
        });
    }
}

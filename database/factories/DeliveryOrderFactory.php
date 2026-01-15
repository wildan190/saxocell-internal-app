<?php

namespace Database\Factories;

use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryOrderFactory extends Factory
{
    protected $model = DeliveryOrder::class;

    public function definition(): array
    {
        $po = PurchaseOrder::factory()->create();

        return [
            'do_number' => 'DO-' . $this->faker->unique()->numerify('######'),
            'purchase_order_id' => $po->id,
            'supplier_id' => $po->supplier_id,
            'delivery_date' => $this->faker->dateTimeBetween($po->order_date, 'now')->format('Y-m-d'),
            'received_by' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'partial', 'completed', 'rejected']),
            'notes' => $this->faker->sentence(),
        ];
    }
}

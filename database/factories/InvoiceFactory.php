<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $po = PurchaseOrder::factory()->create();

        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->numerify('######'),
            'purchase_order_id' => $po->id,
            'supplier_id' => $po->supplier_id,
            'invoice_date' => $this->faker->dateTimeBetween($po->order_date, 'now')->format('Y-m-d'),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'subtotal' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
            'status' => $this->faker->randomElement(['pending', 'matched', 'discrepancy', 'approved', 'paid']),
            'matched_at' => function (array $attributes) {
                return in_array($attributes['status'], ['matched', 'approved', 'paid']) ? now() : null;
            },
            'approved_by' => function (array $attributes) {
                return in_array($attributes['status'], ['approved', 'paid']) ? User::factory() : null;
            },
            'approved_at' => function (array $attributes) {
                return $attributes['approved_by'] ? now() : null;
            },
            'payment_status' => function (array $attributes) {
                return $attributes['status'] === 'paid' ? 'paid' : 'unpaid';
            },
            'notes' => $this->faker->sentence(),
        ];
    }
}

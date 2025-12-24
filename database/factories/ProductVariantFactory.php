<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            ['name' => 'Natural Titanium', 'hex' => '#8B7355'],
            ['name' => 'Blue Titanium', 'hex' => '#4A90E2'],
            ['name' => 'White Titanium', 'hex' => '#F5F5F0'],
            ['name' => 'Black Titanium', 'hex' => '#2C2C2C'],
            ['name' => 'Phantom Black', 'hex' => '#1A1A1A'],
            ['name' => 'Phantom White', 'hex' => '#F8F8F8'],
            ['name' => 'Cream', 'hex' => '#F5F5DC'],
            ['name' => 'Violet', 'hex' => '#8B5CF6'],
            ['name' => 'Obsidian Black', 'hex' => '#0F0F0F'],
            ['name' => 'Pearl White', 'hex' => '#F0F0F0'],
            ['name' => 'Forest Green', 'hex' => '#228B22'],
            ['name' => 'Desert Titanium', 'hex' => '#D2B48C'],
            ['name' => 'Matte Black', 'hex' => '#1C1C1C'],
            ['name' => 'Glacier Blue', 'hex' => '#87CEEB'],
            ['name' => 'Sunset Orange', 'hex' => '#FF6347'],
            ['name' => 'Mint Green', 'hex' => '#98FB98'],
        ];

        $storages = [
            ['size' => '128GB', 'price_modifier' => 0],
            ['size' => '256GB', 'price_modifier' => 100],
            ['size' => '512GB', 'price_modifier' => 200],
            ['size' => '1TB', 'price_modifier' => 400],
        ];

        $color = $this->faker->randomElement($colors);
        $storage = $this->faker->randomElement($storages);

        // Get a random product to attach this variant to
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        // Calculate variant price based on storage
        $basePrice = $product->price;
        $variantPrice = $basePrice + $storage['price_modifier'];

        // Create variant attributes
        $attributes = [
            'color' => $color['name'],
            'color_hex' => $color['hex'],
            'storage' => $storage['size'],
            'network' => $this->faker->randomElement(['Unlocked', 'Verizon', 'AT&T', 'T-Mobile', 'Sprint']),
            'condition' => $product->category === 'new' ? 'New' : $this->faker->randomElement(['Excellent', 'Very Good', 'Good']),
        ];

        // Add RAM info for Android phones
        if (!str_contains($product->name, 'iPhone')) {
            $attributes['ram'] = $this->faker->randomElement(['8GB', '12GB', '16GB']);
        }

        return [
            'product_id' => $product->id,
            'name' => $product->name . ' - ' . $color['name'] . ' - ' . $storage['size'],
            'sku' => null, // Will be auto-generated
            'price' => $variantPrice,
            'stock_quantity' => $this->faker->numberBetween(5, 50),
            'attributes' => $attributes,
            'image' => null, // Could add default images later
            'is_default' => false,
            'status' => 'active',
        ];
    }

    /**
     * Indicate that this variant is the default variant.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Indicate that this variant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Create variants for a specific product.
     */
    public function forProduct(Product $product): static
    {
        return $this->state(fn (array $attributes) => [
            'product_id' => $product->id,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $smartphones = [
            [
                'name' => 'iPhone 15 Pro Max',
                'brand' => 'Apple',
                'base_price' => 1199,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Apple',
                    'Operating System' => 'iOS 17',
                    'Display Size' => '6.7 inches',
                    'Resolution' => '2796 x 1290 pixels',
                    'Processor' => 'A17 Pro Bionic',
                    'RAM' => '8GB',
                    'Storage' => '256GB',
                    'Camera' => '48MP Triple Camera',
                    'Battery' => '4680mAh',
                    'Weight' => '221g',
                    'Material' => 'Titanium'
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'brand' => 'Samsung',
                'base_price' => 1299,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Samsung',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.8 inches',
                    'Resolution' => '3120 x 1440 pixels',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '12GB',
                    'Storage' => '256GB',
                    'Camera' => '200MP Quad Camera',
                    'Battery' => '5000mAh',
                    'Weight' => '232g',
                    'Material' => 'Gorilla Glass Victus 2'
                ]
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'brand' => 'Google',
                'base_price' => 999,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Google',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.7 inches',
                    'Resolution' => '2992 x 1344 pixels',
                    'Processor' => 'Google Tensor G3',
                    'RAM' => '12GB',
                    'Storage' => '128GB',
                    'Camera' => '50MP Triple Camera',
                    'Battery' => '5050mAh',
                    'Weight' => '213g',
                    'Material' => 'Gorilla Glass Victus'
                ]
            ],
            [
                'name' => 'OnePlus 12',
                'brand' => 'OnePlus',
                'base_price' => 799,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'OnePlus',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.82 inches',
                    'Resolution' => '3168 x 1440 pixels',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '16GB',
                    'Storage' => '256GB',
                    'Camera' => '50MP Triple Camera',
                    'Battery' => '5400mAh',
                    'Weight' => '220g',
                    'Material' => 'Gorilla Glass Victus 2'
                ]
            ],
            [
                'name' => 'Xiaomi 14 Ultra',
                'brand' => 'Xiaomi',
                'base_price' => 1199,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Xiaomi',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.73 inches',
                    'Resolution' => '3200 x 1440 pixels',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '16GB',
                    'Storage' => '512GB',
                    'Camera' => '50MP Quad Camera',
                    'Battery' => '5300mAh',
                    'Weight' => '219g',
                    'Material' => 'Leather/Ceramic'
                ]
            ],
            [
                'name' => 'Sony Xperia 1 V',
                'brand' => 'Sony',
                'base_price' => 1299,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Sony',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.5 inches',
                    'Resolution' => '3840 x 1644 pixels',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '12GB',
                    'Storage' => '256GB',
                    'Camera' => '48MP Triple Camera',
                    'Battery' => '5000mAh',
                    'Weight' => '187g',
                    'Material' => 'Gorilla Glass Victus 2'
                ]
            ],
            [
                'name' => 'ASUS ROG Phone 8',
                'brand' => 'ASUS',
                'base_price' => 999,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'ASUS',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.78 inches',
                    'Resolution' => '2488 x 1080 pixels',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'RAM' => '16GB',
                    'Storage' => '512GB',
                    'Camera' => '50MP Triple Camera',
                    'Battery' => '5500mAh',
                    'Weight' => '225g',
                    'Material' => 'Gorilla Glass Victus 2'
                ]
            ],
            [
                'name' => 'Nothing Phone (2a)',
                'brand' => 'Nothing',
                'base_price' => 399,
                'category' => 'new',
                'specs' => [
                    'Brand' => 'Nothing',
                    'Operating System' => 'Android 14',
                    'Display Size' => '6.7 inches',
                    'Resolution' => '2412 x 1084 pixels',
                    'Processor' => 'MediaTek Dimensity 7200 Pro',
                    'RAM' => '8GB',
                    'Storage' => '128GB',
                    'Camera' => '50MP Dual Camera',
                    'Battery' => '5000mAh',
                    'Weight' => '190g',
                    'Material' => 'Gorilla Glass 5'
                ]
            ]
        ];

        $phone = $this->faker->randomElement($smartphones);

        return [
            'name' => $phone['name'],
            'description' => $this->faker->paragraphs(2, true),
            'price' => $phone['base_price'],
            'category' => $phone['category'],
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'draft']),
            'product_specs' => $phone['specs'],
            'sku' => null, // Will be auto-generated
            'stock_quantity' => 0, // Will be calculated from variants
        ];
    }

    /**
     * Indicate that the product is used/refurbished.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'used',
            'price' => $this->faker->numberBetween(200, 800),
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create smartphone products with variants
        $this->createSmartphoneProducts();

        // Seed Procurement Module
        $this->seedProcurement();
    }

    /**
     * Seed the Procurement module using factories.
     */
    private function seedProcurement(): void
    {
        // 1. Create Suppliers
        $suppliers = \App\Models\Supplier::factory()->count(10)->create();

        // 2. Create Purchase Orders with Items
        $pos = \App\Models\PurchaseOrder::factory()
            ->count(15)
            ->sequence(fn ($sequence) => ['supplier_id' => $suppliers->random()->id])
            ->has(\App\Models\PurchaseOrderItem::factory()->count(3), 'items')
            ->create();

        // 3. Update PO totals (factory doesn't update parent PO subtotal/total automatically by default)
        foreach ($pos as $po) {
            $subtotal = $po->items->sum('subtotal');
            $taxAmount = $subtotal * 0.11;
            $po->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $subtotal + $taxAmount + ($po->shipping_cost ?? 0)
            ]);
        }

        // 4. Create Delivery Orders for some approved/completed POs
        $eligiblePos = $pos->whereIn('status', ['approved', 'partial', 'completed']);
        foreach ($eligiblePos->random(min(8, $eligiblePos->count())) as $po) {
            $do = \App\Models\DeliveryOrder::factory()->create([
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
            ]);

            foreach ($po->items as $item) {
                \App\Models\DeliveryOrderItem::factory()->create([
                    'delivery_order_id' => $do->id,
                    'purchase_order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity_delivered' => $item->quantity_ordered,
                    'quantity_accepted' => $item->quantity_ordered,
                ]);
            }
        }

        // 5. Create Invoices for some POs
        foreach ($pos->random(min(10, $pos->count())) as $po) {
            $invoice = \App\Models\Invoice::factory()->create([
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'subtotal' => $po->subtotal,
                'tax_amount' => $po->tax_amount,
                'total_amount' => $po->total_amount,
            ]);

            foreach ($po->items as $item) {
                \App\Models\InvoiceItem::factory()->create([
                    'invoice_id' => $invoice->id,
                    'purchase_order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity_ordered,
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'subtotal' => $item->subtotal,
                ]);
            }
        }
    }

    /**
     * Create smartphone products with realistic variants.
     */
    private function createSmartphoneProducts(): void
    {
        $smartphones = [
            [
                'name' => 'iPhone 15 Pro Max',
                'base_price' => 1199,
                'description' => 'The most advanced iPhone with titanium design, A17 Pro chip, and professional camera system.',
                'variants' => [
                    ['color' => 'Natural Titanium', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 25],
                    ['color' => 'Natural Titanium', 'storage' => '512GB', 'price_mod' => 200, 'stock' => 20],
                    ['color' => 'Natural Titanium', 'storage' => '1TB', 'price_mod' => 400, 'stock' => 15],
                    ['color' => 'Blue Titanium', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 30],
                    ['color' => 'Blue Titanium', 'storage' => '512GB', 'price_mod' => 200, 'stock' => 25],
                    ['color' => 'Blue Titanium', 'storage' => '1TB', 'price_mod' => 400, 'stock' => 18],
                    ['color' => 'White Titanium', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 22],
                    ['color' => 'White Titanium', 'storage' => '512GB', 'price_mod' => 200, 'stock' => 19],
                    ['color' => 'Black Titanium', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 28],
                    ['color' => 'Black Titanium', 'storage' => '512GB', 'price_mod' => 200, 'stock' => 24],
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'base_price' => 1299,
                'description' => 'Premium Android flagship with S Pen, 200MP camera, and AI features.',
                'variants' => [
                    ['color' => 'Phantom Black', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 35],
                    ['color' => 'Phantom Black', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 28],
                    ['color' => 'Phantom Black', 'storage' => '1TB', 'price_mod' => 300, 'stock' => 20],
                    ['color' => 'Phantom White', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 32],
                    ['color' => 'Phantom White', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 26],
                    ['color' => 'Cream', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 30],
                    ['color' => 'Cream', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 25],
                    ['color' => 'Violet', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 27],
                    ['color' => 'Violet', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 22],
                ]
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'base_price' => 999,
                'description' => 'Pure Android experience with exceptional camera and AI capabilities.',
                'variants' => [
                    ['color' => 'Obsidian Black', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 40],
                    ['color' => 'Obsidian Black', 'storage' => '256GB', 'price_mod' => 100, 'stock' => 35],
                    ['color' => 'Obsidian Black', 'storage' => '512GB', 'price_mod' => 200, 'stock' => 25],
                    ['color' => 'Pearl White', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 38],
                    ['color' => 'Pearl White', 'storage' => '256GB', 'price_mod' => 100, 'stock' => 32],
                    ['color' => 'Bay Blue', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 36],
                    ['color' => 'Bay Blue', 'storage' => '256GB', 'price_mod' => 100, 'stock' => 30],
                ]
            ],
            [
                'name' => 'OnePlus 12',
                'base_price' => 799,
                'description' => 'Flagship performance with fast charging and clean OxygenOS.',
                'variants' => [
                    ['color' => 'Silky Black', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 45],
                    ['color' => 'Silky Black', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 38],
                    ['color' => 'Flowy Emerald', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 42],
                    ['color' => 'Flowy Emerald', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 35],
                    ['color' => 'Silky White', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 40],
                    ['color' => 'Silky White', 'storage' => '512GB', 'price_mod' => 100, 'stock' => 33],
                ]
            ],
            [
                'name' => 'Xiaomi 14 Ultra',
                'base_price' => 1199,
                'description' => 'Professional photography flagship with Leica partnership.',
                'variants' => [
                    ['color' => 'Black', 'storage' => '512GB', 'price_mod' => 0, 'stock' => 28],
                    ['color' => 'Black', 'storage' => '1TB', 'price_mod' => 200, 'stock' => 20],
                    ['color' => 'White', 'storage' => '512GB', 'price_mod' => 0, 'stock' => 26],
                    ['color' => 'White', 'storage' => '1TB', 'price_mod' => 200, 'stock' => 18],
                    ['color' => 'Titanium', 'storage' => '512GB', 'price_mod' => 50, 'stock' => 24],
                    ['color' => 'Titanium', 'storage' => '1TB', 'price_mod' => 250, 'stock' => 16],
                ]
            ],
            [
                'name' => 'Sony Xperia 1 V',
                'base_price' => 1299,
                'description' => 'Cinema-quality camera with 4K 120fps recording capabilities.',
                'variants' => [
                    ['color' => 'Black', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 22],
                    ['color' => 'Black', 'storage' => '512GB', 'price_mod' => 150, 'stock' => 18],
                    ['color' => 'Platinum Silver', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 20],
                    ['color' => 'Platinum Silver', 'storage' => '512GB', 'price_mod' => 150, 'stock' => 16],
                    ['color' => 'Forest Green', 'storage' => '256GB', 'price_mod' => 0, 'stock' => 19],
                ]
            ],
            [
                'name' => 'ASUS ROG Phone 8',
                'base_price' => 999,
                'description' => 'Gaming flagship with advanced cooling and GameCool technology.',
                'variants' => [
                    ['color' => 'Phantom Black', 'storage' => '512GB', 'price_mod' => 0, 'stock' => 30],
                    ['color' => 'Phantom Black', 'storage' => '1TB', 'price_mod' => 200, 'stock' => 22],
                    ['color' => 'Storm White', 'storage' => '512GB', 'price_mod' => 0, 'stock' => 28],
                    ['color' => 'Storm White', 'storage' => '1TB', 'price_mod' => 200, 'stock' => 20],
                ]
            ],
            [
                'name' => 'Nothing Phone (2a)',
                'base_price' => 399,
                'description' => 'Unique design with Glyph interface and clean Android experience.',
                'variants' => [
                    ['color' => 'Black', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 50],
                    ['color' => 'Black', 'storage' => '256GB', 'price_mod' => 50, 'stock' => 45],
                    ['color' => 'White', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 48],
                    ['color' => 'White', 'storage' => '256GB', 'price_mod' => 50, 'stock' => 42],
                    ['color' => 'Blue', 'storage' => '128GB', 'price_mod' => 0, 'stock' => 46],
                    ['color' => 'Blue', 'storage' => '256GB', 'price_mod' => 50, 'stock' => 40],
                ]
            ]
        ];

        foreach ($smartphones as $phoneData) {
            // Create the product
            $product = Product::create([
                'name' => $phoneData['name'],
                'description' => $phoneData['description'],
                'price' => $phoneData['base_price'],
                'category' => 'new',
                'status' => 'active',
                'product_specs' => $this->getSmartphoneSpecs($phoneData['name']),
                'stock_quantity' => 0, // Will be calculated from variants
            ]);

            // Generate SKU for product
            $product->update(['sku' => $product->generateSku()]);

            // Create variants
            $isFirstVariant = true;
            foreach ($phoneData['variants'] as $variantData) {
                $variant = new ProductVariant([
                    'product_id' => $product->id,
                    'name' => $product->name . ' - ' . $variantData['color'] . ' - ' . $variantData['storage'],
                    'price' => $phoneData['base_price'] + $variantData['price_mod'],
                    'stock_quantity' => $variantData['stock'],
                    'attributes' => [
                        'color' => $variantData['color'],
                        'storage' => $variantData['storage'],
                        'network' => 'Unlocked',
                        'condition' => 'New',
                        'ram' => str_contains($product->name, 'iPhone') ? null : $this->getRamForPhone($product->name),
                    ],
                    'is_default' => $isFirstVariant,
                    'status' => 'active',
                ]);

                // Generate SKU for variant before saving
                $variant->sku = $product->generateVariantSku($variant);
                $variant->save();

                $isFirstVariant = false;
            }
        }

        // Create some used/refurbished phones
        $this->createUsedSmartphones();
    }

    /**
     * Get detailed specs for a smartphone.
     */
    private function getSmartphoneSpecs(string $phoneName): array
    {
        $specs = [
            'iPhone 15 Pro Max' => [
                'Brand' => 'Apple',
                'Operating System' => 'iOS 17',
                'Display Size' => '6.7 inches',
                'Resolution' => '2796 x 1290 pixels',
                'Processor' => 'A17 Pro Bionic',
                'RAM' => '8GB',
                'Storage' => '256GB/512GB/1TB',
                'Camera' => '48MP Triple Camera',
                'Battery' => '4680mAh',
                'Weight' => '221g',
                'Material' => 'Titanium',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'Wireless Charging' => 'Yes',
            ],
            'Samsung Galaxy S24 Ultra' => [
                'Brand' => 'Samsung',
                'Operating System' => 'Android 14',
                'Display Size' => '6.8 inches',
                'Resolution' => '3120 x 1440 pixels',
                'Processor' => 'Snapdragon 8 Gen 3',
                'RAM' => '12GB',
                'Storage' => '256GB/512GB/1TB',
                'Camera' => '200MP Quad Camera',
                'Battery' => '5000mAh',
                'Weight' => '232g',
                'Material' => 'Gorilla Glass Victus 2',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'S Pen' => 'Included',
                'Wireless Charging' => 'Yes',
            ],
            'Google Pixel 8 Pro' => [
                'Brand' => 'Google',
                'Operating System' => 'Android 14',
                'Display Size' => '6.7 inches',
                'Resolution' => '2992 x 1344 pixels',
                'Processor' => 'Google Tensor G3',
                'RAM' => '12GB',
                'Storage' => '128GB/256GB/512GB',
                'Camera' => '50MP Triple Camera',
                'Battery' => '5050mAh',
                'Weight' => '213g',
                'Material' => 'Gorilla Glass Victus',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'Google AI' => 'Yes',
                'Wireless Charging' => 'Yes',
            ],
            'OnePlus 12' => [
                'Brand' => 'OnePlus',
                'Operating System' => 'Android 14',
                'Display Size' => '6.82 inches',
                'Resolution' => '3168 x 1440 pixels',
                'Processor' => 'Snapdragon 8 Gen 3',
                'RAM' => '16GB',
                'Storage' => '256GB/512GB',
                'Camera' => '50MP Triple Camera',
                'Battery' => '5400mAh',
                'Weight' => '220g',
                'Material' => 'Gorilla Glass Victus 2',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'Fast Charging' => '100W',
                'Wireless Charging' => 'Yes',
            ],
            'Xiaomi 14 Ultra' => [
                'Brand' => 'Xiaomi',
                'Operating System' => 'Android 14',
                'Display Size' => '6.73 inches',
                'Resolution' => '3200 x 1440 pixels',
                'Processor' => 'Snapdragon 8 Gen 3',
                'RAM' => '16GB',
                'Storage' => '512GB/1TB',
                'Camera' => '50MP Quad Camera (Leica)',
                'Battery' => '5300mAh',
                'Weight' => '219g',
                'Material' => 'Leather/Ceramic',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'Fast Charging' => '90W',
                'Wireless Charging' => 'Yes',
            ],
            'Sony Xperia 1 V' => [
                'Brand' => 'Sony',
                'Operating System' => 'Android 14',
                'Display Size' => '6.5 inches',
                'Resolution' => '3840 x 1644 pixels',
                'Processor' => 'Snapdragon 8 Gen 3',
                'RAM' => '12GB',
                'Storage' => '256GB/512GB',
                'Camera' => '48MP Triple Camera',
                'Battery' => '5000mAh',
                'Weight' => '187g',
                'Material' => 'Gorilla Glass Victus 2',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                '4K Video' => '120fps',
                'Wireless Charging' => 'Yes',
            ],
            'ASUS ROG Phone 8' => [
                'Brand' => 'ASUS',
                'Operating System' => 'Android 14',
                'Display Size' => '6.78 inches',
                'Resolution' => '2488 x 1080 pixels',
                'Processor' => 'Snapdragon 8 Gen 3',
                'RAM' => '16GB',
                'Storage' => '512GB/1TB',
                'Camera' => '50MP Triple Camera',
                'Battery' => '5500mAh',
                'Weight' => '225g',
                'Material' => 'Gorilla Glass Victus 2',
                'Water Resistance' => 'IP68',
                '5G' => 'Yes',
                'Gaming Features' => 'AirTriggers, GameCool',
                'Fast Charging' => '65W',
                'Wireless Charging' => 'Yes',
            ],
            'Nothing Phone (2a)' => [
                'Brand' => 'Nothing',
                'Operating System' => 'Android 14',
                'Display Size' => '6.7 inches',
                'Resolution' => '2412 x 1084 pixels',
                'Processor' => 'MediaTek Dimensity 7200 Pro',
                'RAM' => '8GB',
                'Storage' => '128GB/256GB',
                'Camera' => '50MP Dual Camera',
                'Battery' => '5000mAh',
                'Weight' => '190g',
                'Material' => 'Gorilla Glass 5',
                'Water Resistance' => 'IP54',
                '5G' => 'Yes',
                'Glyph Interface' => 'Yes',
                'Wireless Charging' => 'Yes',
            ],
        ];

        return $specs[$phoneName] ?? [];
    }

    /**
     * Get RAM specification for Android phones.
     */
    private function getRamForPhone(string $phoneName): string
    {
        $ramSpecs = [
            'Samsung Galaxy S24 Ultra' => '12GB',
            'Google Pixel 8 Pro' => '12GB',
            'OnePlus 12' => '16GB',
            'Xiaomi 14 Ultra' => '16GB',
            'Sony Xperia 1 V' => '12GB',
            'ASUS ROG Phone 8' => '16GB',
            'Nothing Phone (2a)' => '8GB',
        ];

        return $ramSpecs[$phoneName] ?? '8GB';
    }

    /**
     * Create some used/refurbished smartphones.
     */
    private function createUsedSmartphones(): void
    {
        $usedPhones = [
            [
                'name' => 'iPhone 14 Pro Max (Used)',
                'base_price' => 899,
                'original_price' => 1099,
                'condition' => 'Excellent',
                'variants' => [
                    ['color' => 'Deep Purple', 'storage' => '256GB', 'stock' => 8],
                    ['color' => 'Space Black', 'storage' => '512GB', 'stock' => 6],
                    ['color' => 'Gold', 'storage' => '256GB', 'stock' => 5],
                ]
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra (Refurbished)',
                'base_price' => 799,
                'original_price' => 1199,
                'condition' => 'Very Good',
                'variants' => [
                    ['color' => 'Phantom Black', 'storage' => '256GB', 'stock' => 12],
                    ['color' => 'Cream', 'storage' => '512GB', 'stock' => 9],
                    ['color' => 'Green', 'storage' => '256GB', 'stock' => 7],
                ]
            ],
            [
                'name' => 'Google Pixel 7 Pro (Used)',
                'base_price' => 599,
                'original_price' => 899,
                'condition' => 'Good',
                'variants' => [
                    ['color' => 'Obsidian', 'storage' => '128GB', 'stock' => 15],
                    ['color' => 'Snow', 'storage' => '256GB', 'stock' => 10],
                ]
            ],
        ];

        foreach ($usedPhones as $phoneData) {
            $product = Product::create([
                'name' => $phoneData['name'],
                'description' => "Refurbished {$phoneData['condition']} condition. Original price: \${$phoneData['original_price']}. Fully tested and guaranteed.",
                'price' => $phoneData['base_price'],
                'category' => 'used',
                'status' => 'active',
                'product_specs' => array_merge(
                    $this->getSmartphoneSpecs(str_replace([' (Used)', ' (Refurbished)'], '', $phoneData['name'])),
                    ['Condition' => $phoneData['condition'], 'Warranty' => '90 Days']
                ),
                'stock_quantity' => 0,
            ]);

            $product->update(['sku' => $product->generateSku()]);

            $isFirstVariant = true;
            foreach ($phoneData['variants'] as $variantData) {
                $variant = new ProductVariant([
                    'product_id' => $product->id,
                    'name' => str_replace([' (Used)', ' (Refurbished)'], '', $product->name) . ' - ' . $variantData['color'] . ' - ' . $variantData['storage'] . ' (' . $phoneData['condition'] . ')',
                    'price' => $phoneData['base_price'],
                    'stock_quantity' => $variantData['stock'],
                    'attributes' => [
                        'color' => $variantData['color'],
                        'storage' => $variantData['storage'],
                        'network' => 'Unlocked',
                        'condition' => $phoneData['condition'],
                        'warranty' => '90 Days',
                    ],
                    'is_default' => $isFirstVariant,
                    'status' => 'active',
                ]);

                // Generate SKU for variant before saving
                $variant->sku = $product->generateVariantSku($variant);
                $variant->save();

                $isFirstVariant = false;
            }
        }
    }
}

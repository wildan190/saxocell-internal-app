<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = Product::query();

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        // Handle specs
        $data['product_specs'] = $this->formatSpecs($data);

        // Handle image
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        $product = Product::create($data);

        // Generate SKU
        if (!$product->sku) {
            $product->update(['sku' => $product->generateSku()]);
        }

        // Handle variants
        if (isset($data['has_variants']) && $data['has_variants'] && isset($data['variants'])) {
            $this->createVariants($product, $data['variants']);
        }

        return $product;
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);

        // Handle specs
        $data['product_specs'] = $this->formatSpecs($data);

        // Handle image
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $data['image']->store('products', 'public');
        }

        $product->update($data);

        // Handle variants
        if (isset($data['has_variants']) && $data['has_variants'] && isset($data['variants'])) {
            $this->updateVariants($product, $data['variants']);
        } else {
            $product->variants()->delete();
        }

        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }

    private function formatSpecs(array $data)
    {
        return $this->formatKeyValuePairs(
            $data['spec_keys'] ?? [],
            $data['spec_values'] ?? []
        );
    }

    private function createVariants(Product $product, array $variantsData)
    {
        foreach ($variantsData as $variantData) {
            $variantImage = null;
            if (isset($variantData['image']) && $variantData['image']) {
                $variantImage = $variantData['image']->store('product-variants', 'public');
            }

            $variant = $product->variants()->create([
                'name' => $variantData['name'],
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'] ?? null,
                'stock_quantity' => $variantData['stock_quantity'],
                'attributes' => $this->formatKeyValuePairs(
                    $variantData['attribute_keys'] ?? [],
                    $variantData['attribute_values'] ?? []
                ),
                'image' => $variantImage,
                'is_default' => $variantData['is_default'] ?? false,
                'status' => 'active',
            ]);

            if (!$variant->sku) {
                $variant->update(['sku' => $product->generateVariantSku($variant)]);
            }
        }
    }

    private function updateVariants(Product $product, array $variantsData)
    {
        $existingVariantIds = [];

        foreach ($variantsData as $variantData) {
            if (isset($variantData['id']) && $variantData['id']) {
                // Update existing
                $variant = $product->variants()->find($variantData['id']);
                if ($variant) {
                    if (isset($variantData['image']) && $variantData['image']) {
                        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $variantData['image'] = $variantData['image']->store('product-variants', 'public');
                    } else {
                        unset($variantData['image']);
                    }

                    if (isset($variantData['attribute_keys']) || isset($variantData['attribute_values'])) {
                        $variantData['attributes'] = $this->formatKeyValuePairs(
                            $variantData['attribute_keys'] ?? [],
                            $variantData['attribute_values'] ?? []
                        );
                    } elseif (isset($variantData['attributes']) && is_string($variantData['attributes'])) {
                        // Fallback for legacy JSON string input (if any)
                         $decoded = json_decode($variantData['attributes'], true);
                         $variantData['attributes'] = is_array($decoded) ? $decoded : [];
                    }

                    $variant->update($variantData);
                    $existingVariantIds[] = $variant->id;
                }
            } else {
                // Create new
                $variantImage = null;
                if (isset($variantData['image']) && $variantData['image']) {
                    $variantImage = $variantData['image']->store('product-variants', 'public');
                }

                $variant = $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'] ?? null,
                    'price' => $variantData['price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'attributes' => $this->formatKeyValuePairs(
                        $variantData['attribute_keys'] ?? [],
                        $variantData['attribute_values'] ?? []
                    ),
                    'image' => $variantImage,
                    'is_default' => $variantData['is_default'] ?? false,
                    'status' => 'active',
                ]);

                if (!$variant->sku) {
                    $variant->update(['sku' => $product->generateVariantSku($variant)]);
                }

                $existingVariantIds[] = $variant->id;
            }
        }

        $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
    }
    private function formatKeyValuePairs($keys, $values)
    {
        $result = [];
        if (is_array($keys) && is_array($values)) {
            foreach ($keys as $index => $key) {
                if (!empty($key) && isset($values[$index]) && !empty($values[$index])) {
                    $result[$key] = $values[$index];
                }
            }
        }
        return $result;
    }
}

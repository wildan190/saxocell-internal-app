<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description_1' => 'nullable|string',
            'description_2' => 'nullable|string',
            'description_3' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:new,used',
            'status' => 'required|in:active,inactive,draft',
            'sku' => 'nullable|string|max:100',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'spec_keys.*' => 'nullable|string|max:100',
            'spec_values.*' => 'nullable|string|max:255',
            // Variant validation
            'has_variants' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'required_if:has_variants,1|string|max:255',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required_if:has_variants,1|integer|min:0',
            'variants.*.attribute_keys' => 'nullable|array',
            'variants.*.attribute_keys.*' => 'nullable|string|max:100',
            'variants.*.attribute_values' => 'nullable|array',
            'variants.*.attribute_values.*' => 'nullable|string|max:255',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants.*.is_default' => 'boolean',
        ];
    }
}

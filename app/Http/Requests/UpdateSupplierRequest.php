<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|unique:suppliers,email,' . $this->supplier,
            'phone' => 'required|string|max:255',
            'address' => 'nullable|string',
            'identity_card_number' => 'nullable|string|max:255',
        ];
    }
}

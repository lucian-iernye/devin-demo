<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('suppliers.manage') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2'],
            'region' => ['nullable', 'string', 'max:255'],
            'generation_mix' => ['nullable', 'array'],
            'generation_mix.*' => ['numeric', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending_kyc,active,suspended'],
        ];
    }
}

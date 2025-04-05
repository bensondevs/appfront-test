<?php

namespace App\Http\Requests\Admin\Product\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;

trait HasProduct
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule | array<int, mixed> |string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }

    /**
     * Get product data without image.
     */
    public function productData(): array
    {
        return $this->only(['name', 'description', 'price']);
    }
}

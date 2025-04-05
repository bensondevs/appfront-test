<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\Admin\Product\Concerns\HasProduct;
use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
{
    use HasProduct;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

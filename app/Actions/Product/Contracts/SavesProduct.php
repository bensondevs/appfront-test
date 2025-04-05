<?php

namespace App\Actions\Product\Contracts;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

interface SavesProduct
{
    public function __invoke(
        Product       $product,
        array         $data,
        ?UploadedFile $image = null,
    ): Product;
}
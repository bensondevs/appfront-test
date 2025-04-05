<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class SaveProduct implements Contracts\SavesProduct
{
    public function __invoke(
        Product $product,
        array $data,
        ?UploadedFile $image = null,
    ): void
    {
        $product->fill($data);

        if ($image instanceof UploadedFile) {
            $product->setImageFromFileRequest($image);
        }

        $product->save();
    }
}
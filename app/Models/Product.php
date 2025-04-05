<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

#[ObservedBy(ProductObserver::class)]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected const IMAGE_DIRECTORY_NAME = 'uploads';

    protected $guarded = ['id'];

    public function casts(): array
    {
        return [
            'price' => 'float',
        ];
    }

    /**
     * Set image from file request.
     *
     * @param UploadedFile $image
     * @return void
     *
     * @TODO Refactor this into storage based upload and drop `image` database column
     */
    public function setImageFromFileRequest(UploadedFile $image): void
    {
        $filename = now()->timestamp . '-' . $image->getClientOriginalName();

        $directoryName = self::IMAGE_DIRECTORY_NAME;

        $folderPath = public_path($directoryName);
        $image->move($folderPath, $filename);

        $this->image = $directoryName . '/' . $filename;
    }

    /**
     * Get image path of the product.
     *
     * @return string
     */
    public function getImage(): string
    {
        $image = $this->image;
        $imageDirectory = public_path($image);

        return file_exists($imageDirectory) && getimagesize($imageDirectory)
            ? $image
            : 'product-placeholder.jpg';
    }
}

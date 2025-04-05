<?php

namespace App\Observers;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->isDirty('price')) {
            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $product->getOriginal('price'),
                    $product->price,
                );
            } catch (\Exception $exception) {
                Log::error('Failed to dispatch price change notification: ' . $exception->getMessage());
            }
        }
    }
}

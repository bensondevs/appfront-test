<?php

namespace App\Jobs;

use App\Mail\PriceChangeNotification;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPriceChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Product $product,
        protected float $oldPrice,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(config('services.product.price-notification-email'))->send(new PriceChangeNotification(
            $this->product,
            $this->oldPrice
        ));
    }
}

<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Mail\Mailable;

class PriceChangeNotification extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected Product $product,
        protected float $oldPrice,
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->subject('Product Price Change Notification')
            ->view('emails.price-change', [
                'product' => $this->product,
                'oldPrice' => $this->oldPrice,
            ]);
    }
}

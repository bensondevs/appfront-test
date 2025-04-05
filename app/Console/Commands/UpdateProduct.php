<?php

namespace App\Console\Commands;

use App\Actions\Product\Contracts\SavesProduct;
use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Log;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param SavesProduct $savesProduct
     * @return int
     */
    public function handle(SavesProduct $savesProduct): int
    {
        $product = Product::query()->find($this->argument('id'));

        if (! $product instanceof Product) {
            $this->error('Product not found!');

            return 1;
        }

        $data = collect([
            'name' => $this->option('name'),
            'description' => $this->option('description'),
            'price' => $this->option('price'),
        ])->filter(fn ($value) => ! is_null($value))->all();

        if (isset($data['name'])) {
            $name = trim($data['name']);
            if ($name === '') {
                $this->error("Name cannot be empty.");
                return 1;
            }

            if (strlen($name) < 3) {
                $this->error("Name must be at least 3 characters long.");
                return 1;
            }
            $data['name'] = $name;
        }

        $oldPrice = $product->price;

        if (empty($data)) {
            $this->info("No changes provided. Product remains unchanged.");
            return 0;
        }

        $updatedProduct = $savesProduct($product, $data);
        $this->info("Product updated successfully.");

        $newPrice = $updatedProduct->price;
        if (isset($data['price']) && $oldPrice != $newPrice) {
            $this->info("Price changed from {$oldPrice} to {$newPrice}.");
        }

        return 0;
    }
}

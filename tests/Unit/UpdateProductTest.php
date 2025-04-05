<?php

namespace Tests\Unit;

use App\Actions\Product\Contracts\SavesProduct;
use App\Console\Commands\UpdateProduct;
use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();
    }

    public function test_it_updates_a_product_successfully()
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old Desc',
            'price' => 100,
        ]);

        $mock = $this->mock(SavesProduct::class, function ($mock) use ($product) {
            $mock->shouldReceive('__invoke')
                ->once()
                ->withArgs(function ($prod, $data) use ($product) {
                    $this->assertEquals($product->id, $prod->id);
                    $this->assertSame('New Name', $data['name']);
                    $this->assertSame('New Desc', $data['description']);
                    $this->assertSame(200, $data['price']);

                    $prod->fill($data);

                    return true;
                })
                ->andReturnUsing(fn ($prod) => $prod);
        });

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->id,
            '--name' => 'New Name',
            '--description' => 'New Desc',
            '--price' => 200,
        ]);

        $output = Artisan::output();

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Product updated successfully.', $output);
        $this->assertStringContainsString('Price changed from 100 to 200.', $output);
    }

    public function test_it_fails_when_name_is_empty()
    {
        $product = Product::factory()->create();

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->id,
            '--name' => '   ',
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Name cannot be empty.', Artisan::output());
    }

    public function test_it_fails_when_name_is_too_short()
    {
        $product = Product::factory()->create();

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->id,
            '--name' => 'ab',
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Name must be at least 3 characters long.', Artisan::output());
    }

    public function test_it_shows_no_change_message_if_no_data_is_provided()
    {
        $product = Product::factory()->create();

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->id,
        ]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('No changes provided. Product remains unchanged.', Artisan::output());
    }

    public function test_it_fails_if_product_not_found()
    {
        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => 999,
        ]);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Product not found!', Artisan::output());
    }

    public function test_it_dispatches_price_change_notification_when_price_is_changed()
    {
        Bus::fake();

        $product = Product::factory()->create([
            'name' => 'Original Name',
            'price' => 100,
        ]);

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->getKey(),
            '--price' => 150,
        ]);

        $this->assertEquals(0, $exitCode);

        $output = Artisan::output();
        $this->assertStringContainsString('Product updated successfully.', $output);
        $this->assertStringContainsString('Price changed from 100 to 150.', $output);

        Bus::assertDispatched(SendPriceChangeNotification::class);
    }

    public function test_it_does_not_dispatch_notification_when_price_does_not_change()
    {
        Bus::fake();

        $product = Product::factory()->create([
            'price' => 100,
        ]);

        $exitCode = Artisan::call(UpdateProduct::class, [
            'id' => $product->id,
            '--description' => 'Updated desc',
        ]);

        $this->assertEquals(0, $exitCode);
        Bus::assertNotDispatched(SendPriceChangeNotification::class);
    }
}

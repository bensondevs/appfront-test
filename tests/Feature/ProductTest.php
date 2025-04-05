<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        Product::factory()->count(3)->create();

        Http::fake(['*' => Http::response(['rates' => ['EUR' => 0.85]])]);

        $response = $this->get(route('index'));

        $response->assertOk();
        $response->assertViewIs('products.list');
        $response->assertViewHas('products');
        $response->assertViewHas('exchangeRate', 0.85);
    }

    public function test_show(): void
    {
        $product = Product::factory()->create();

        Http::fake(['*' => Http::response(['rates' => ['EUR' => 0.85]])]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertViewIs('products.show');
        $response->assertViewHas('product', $product);
        $response->assertViewHas('exchangeRate', 0.85);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Support\ExchangeRate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

/**
 * @see \Tests\Feature\ProductTest
 */
class ProductController extends Controller
{
    public function index(): View|Application|Factory
    {
        $products = ProductResource::collection(Product::all());
        $exchangeRate = ExchangeRate::getUsdToEurRate();

        return view('products.list', [
            'products' => $products,
            'exchangeRate' => $exchangeRate,
        ]);
    }

    public function show(Product $product): View|Application|Factory
    {
        $exchangeRate = ExchangeRate::getUsdToEurRate();

        return view('products.show', compact('product', 'exchangeRate'));
    }
}

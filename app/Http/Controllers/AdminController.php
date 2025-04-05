<?php

namespace App\Http\Controllers;

use App\Actions\Product\Contracts\SavesProduct;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\Product\AddProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @see \Tests\Feature\AdminTest
 */
class AdminController extends Controller
{
    public function loginPage(): View|Application|Factory
    {
        return view('login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->validated())) {
            return redirect()->back()->with('error', 'Invalid login credentials');
        }

        return redirect()->route('admin.products');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function products(): View|Application|Factory
    {
        $products = Product::all();

        return view('admin.products', compact('products'));
    }

    public function editProduct(Product $product): View|Application|Factory
    {
        return view('admin.edit_product', compact('product'));
    }

    public function updateProduct(
        UpdateProductRequest $request,
        Product $product,
        SavesProduct $savesProduct,
    ): RedirectResponse {
        $savesProduct(
            $product,
            $request->productData(),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product updated successfully');
    }

    public function deleteProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product deleted successfully');
    }

    public function addProductForm(): View|Application|Factory
    {
        return view('admin.add_product');
    }

    public function addProduct(
        AddProductRequest $request,
        SavesProduct $savesProduct,
    ): RedirectResponse {
        $savesProduct(
            new Product,
            $request->productData(),
            $request->file('image'),
        );

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product added successfully');
    }
}

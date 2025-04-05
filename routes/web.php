<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('index');

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [AdminController::class, 'loginPage'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/products', [AdminController::class, 'products'])
        ->name('admin.products');

    Route::get('/admin/products/add', [AdminController::class, 'addProductForm'])
        ->name('admin.add.product');

    Route::post('/admin/products/add', [AdminController::class, 'addProduct'])
        ->name('admin.add.product.submit');

    Route::get('/admin/products/edit/{product}', [AdminController::class, 'editProduct'])
        ->name('admin.edit.product');

    Route::match(['PUT', 'PATCH'], '/admin/products/edit/{product}', [AdminController::class, 'updateProduct'])
        ->name('admin.update.product');

    Route::delete('/admin/products/delete/{product}', [AdminController::class, 'deleteProduct'])
        ->name('admin.delete.product');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

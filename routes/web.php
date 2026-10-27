<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{product}', [HomeController::class, 'product'])->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{productId}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::prefix('penjual')->name('seller.')->group(function () {
        Route::get('/toko/buat', [SellerStoreController::class, 'create'])->name('store.create');
        Route::post('/toko', [SellerStoreController::class, 'store'])->name('store.store');
        Route::get('/toko', [SellerStoreController::class, 'edit'])->name('store.edit');
        Route::put('/toko', [SellerStoreController::class, 'update'])->name('store.update');

        Route::get('/produk', [SellerProductController::class, 'index'])->name('products.index');
        Route::get('/produk/buat', [SellerProductController::class, 'create'])->name('products.create');
        Route::post('/produk', [SellerProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{product}/ubah', [SellerProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{product}', [SellerProductController::class, 'update'])->name('products.update');
        Route::delete('/produk/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/kategori', [SellerCategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [SellerCategoryController::class, 'store'])->name('categories.store');
        Route::delete('/kategori/{category}', [SellerCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/pesanan', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::patch('/pesanan/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.status');
    });
});

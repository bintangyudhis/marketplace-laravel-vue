<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [ProductController::class, 'categories']);

Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart', [CartController::class, 'add']);
Route::patch('/cart/{productId}', [CartController::class, 'update']);
Route::delete('/cart/{productId}', [CartController::class, 'remove']);

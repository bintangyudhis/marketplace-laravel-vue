<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Etalase publik. Daftar produk dirender komponen Vue yang mengambil
     * data dari API, halaman ini hanya menyediakan kerangka dan kategori.
     */
    public function index(): View
    {
        return view('catalog', [
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['store', 'category']);

        return view('products.show', ['product' => $product]);
    }
}

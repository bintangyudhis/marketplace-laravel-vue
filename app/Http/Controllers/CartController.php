<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', 'Stok produk sudah habis.');
        }

        $this->cart->add($product, $data['quantity'] ?? 1);

        return redirect()->route('cart.index')->with('success', $product->name.' ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $productId): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $product = Product::findOrFail($productId);
        $this->cart->set($productId, min($data['quantity'], $product->stock));

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui.');
    }

    public function remove(string $productId): RedirectResponse
    {
        $this->cart->remove($productId);

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}

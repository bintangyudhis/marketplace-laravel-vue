<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Keranjang belanja berbasis sesi.
 *
 * Isi sesi berupa pasangan product_id => jumlah. Harga dan subtotal selalu
 * dihitung ulang dari database agar tidak bisa dimanipulasi dari klien.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->raw();
        $current = $cart[$product->id] ?? 0;
        $cart[$product->id] = min($current + $quantity, $product->stock);
        session([self::SESSION_KEY => $cart]);
    }

    public function set(string $productId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(string $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Item keranjang yang masih valid (produk aktif) lengkap dengan subtotal.
     *
     * @return Collection<int, array{product: Product, quantity: int, subtotal: int}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if ($cart === []) {
            return collect();
        }

        $products = Product::with('store')->active()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)
            ->filter(fn ($quantity, $productId) => $products->has($productId))
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products[$productId];
                $quantity = min($quantity, $product->stock);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })
            ->filter(fn ($item) => $item['quantity'] > 0)
            ->values();
    }

    public function count(): int
    {
        return $this->items()->sum('quantity');
    }

    public function total(): int
    {
        return $this->items()->sum('subtotal');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function show(): JsonResponse
    {
        return ResponseHelper::jsonResponse(true, 'Isi keranjang berhasil diambil.', $this->payload(), 200);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'uuid'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->stock < 1) {
            return ResponseHelper::jsonResponse(false, 'Stok produk sudah habis.', null, 422);
        }

        $this->cart->add($product, $data['quantity'] ?? 1);

        return ResponseHelper::jsonResponse(true, $product->name.' ditambahkan ke keranjang.', $this->payload(), 200);
    }

    public function update(Request $request, string $productId): JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $product = Product::findOrFail($productId);
        $quantity = min($data['quantity'], $product->stock);
        $this->cart->set($productId, $quantity);

        return ResponseHelper::jsonResponse(true, 'Keranjang diperbarui.', $this->payload(), 200);
    }

    public function remove(string $productId): JsonResponse
    {
        $this->cart->remove($productId);

        return ResponseHelper::jsonResponse(true, 'Produk dihapus dari keranjang.', $this->payload(), 200);
    }

    private function payload(): array
    {
        $items = $this->cart->items();

        return [
            'items' => $items->map(fn ($item) => [
                'product_id' => $item['product']->id,
                'name' => $item['product']->name,
                'price' => $item['product']->price,
                'price_formatted' => 'Rp '.number_format($item['product']->price, 0, ',', '.'),
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'subtotal_formatted' => 'Rp '.number_format($item['subtotal'], 0, ',', '.'),
                'image_url' => $item['product']->imageUrl(),
                'detail_url' => route('products.show', $item['product']),
                'store_name' => $item['product']->store?->name,
            ])->all(),
            'count' => $this->cart->count(),
            'total' => $this->cart->total(),
            'total_formatted' => 'Rp '.number_format($this->cart->total(), 0, ',', '.'),
        ];
    }
}

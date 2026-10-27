<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($request, $data, $items) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'code' => 'INV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'status' => Order::STATUS_BARU,
                'total_price' => $items->sum('subtotal'),
                'recipient_name' => $data['recipient_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'store_id' => $product->store_id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan '.$order->code.' berhasil dibuat.');
    }
}

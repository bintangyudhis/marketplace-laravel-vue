<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $store = $request->user()->store;
        abort_unless($store, 404, 'Buat toko terlebih dahulu.');

        $orders = Order::whereHas('items', fn ($query) => $query->where('store_id', $store->id))
            ->with(['user', 'items' => fn ($query) => $query->where('store_id', $store->id)])
            ->latest()
            ->paginate(10);

        return view('seller.orders.index', ['store' => $store, 'orders' => $orders]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $store = $request->user()->store;
        abort_unless($store, 404);

        $involvesStore = $order->items()->where('store_id', $store->id)->exists();
        abort_unless($involvesStore, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Status pesanan '.$order->code.' diubah menjadi '.$data['status'].'.');
    }
}

@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
    @include('seller.partials.nav')
    <h1 class="text-2xl font-bold">Pesanan Masuk ke {{ $store->name }}</h1>

    @if ($orders->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <p class="text-gray-500">Belum ada pesanan masuk.</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($orders as $order)
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold">{{ $order->code }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }} - Pembeli: {{ $order->user->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('seller.orders.status', $order) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="rounded-lg border border-gray-300 px-2 py-1.5 text-sm capitalize">
                                @foreach (\App\Models\Order::STATUSES as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-gray-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-700">Ubah Status</button>
                        </form>
                    </div>
                    <ul class="mt-3 divide-y divide-gray-100 text-sm">
                        @foreach ($order->items as $item)
                            <li class="flex justify-between py-2">
                                <span>{{ $item->product_name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                                <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-2 text-xs text-gray-400">Dikirim ke {{ $order->recipient_name }}, {{ $order->address }} ({{ $order->phone }})</p>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection

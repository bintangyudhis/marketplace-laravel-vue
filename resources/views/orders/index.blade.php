@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <h1 class="text-2xl font-bold">Pesanan Saya</h1>

    @if ($orders->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <p class="text-gray-500">Belum ada pesanan.</p>
            <a href="{{ route('home') }}" class="mt-3 inline-block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Mulai Belanja</a>
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white px-5 py-4 hover:border-emerald-400">
                    <div>
                        <p class="font-semibold">{{ $order->code }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y H:i') }} - {{ $order->items_count }} item</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <span class="mt-1 inline-block rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium capitalize text-gray-600">{{ $order->status }}</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
@endsection

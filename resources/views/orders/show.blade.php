@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-emerald-700 hover:underline">Kembali ke daftar pesanan</a>

    <div class="mt-4 rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold">{{ $order->code }}</h1>
                <p class="text-sm text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold capitalize text-emerald-700">{{ $order->status }}</span>
        </div>

        <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
            <div>
                <p class="font-medium text-gray-500">Penerima</p>
                <p class="mt-1">{{ $order->recipient_name }} ({{ $order->phone }})</p>
                <p class="mt-1 whitespace-pre-line">{{ $order->address }}</p>
            </div>
            @if ($order->note)
                <div>
                    <p class="font-medium text-gray-500">Catatan</p>
                    <p class="mt-1">{{ $order->note }}</p>
                </div>
            @endif
        </div>

        <table class="mt-6 w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-2 font-medium">Produk</th>
                    <th class="px-4 py-2 font-medium">Toko</th>
                    <th class="px-4 py-2 font-medium">Harga</th>
                    <th class="px-4 py-2 font-medium">Jumlah</th>
                    <th class="px-4 py-2 font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $item->product_name }}</td>
                        <td class="px-4 py-2">{{ $item->store->name }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="4" class="px-4 py-2 text-right font-semibold">Total</td>
                    <td class="px-4 py-2 font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1 class="text-2xl font-bold">Keranjang Belanja</h1>

    @if ($items->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <p class="text-gray-500">Keranjang masih kosong.</p>
            <a href="{{ route('home') }}" class="mt-3 inline-block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Lihat Etalase</a>
        </div>
    @else
        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Produk</th>
                        <th class="px-4 py-3 font-medium">Harga</th>
                        <th class="px-4 py-3 font-medium">Jumlah</th>
                        <th class="px-4 py-3 font-medium">Subtotal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('products.show', $item['product']) }}" class="font-medium hover:text-emerald-700">{{ $item['product']->name }}</a>
                                <p class="text-xs text-gray-400">{{ $item['product']->store->name }}</p>
                            </td>
                            <td class="px-4 py-3">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('cart.update', $item['product']->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $item['product']->stock }}" class="w-16 rounded-lg border border-gray-300 px-2 py-1 text-sm">
                                    <button type="submit" class="text-xs font-medium text-emerald-700 hover:underline">Ubah</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right font-semibold">Total</td>
                        <td colspan="2" class="px-4 py-3 font-bold text-emerald-700">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-bold">Checkout</h2>
            @auth
                <form method="POST" action="{{ route('checkout.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium" for="recipient_name">Nama penerima</label>
                        <input id="recipient_name" type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @error('recipient_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium" for="phone">Nomor telepon</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium" for="address">Alamat pengiriman</label>
                        <textarea id="address" name="address" rows="3" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('address') }}</textarea>
                        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium" for="note">Catatan (opsional)</label>
                        <input id="note" type="text" name="note" value="{{ old('note') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Buat Pesanan</button>
                    </div>
                </form>
            @else
                <p class="mt-3 text-sm text-gray-600">Masuk terlebih dahulu untuk menyelesaikan pesanan.</p>
                <a href="{{ route('login') }}" class="mt-3 inline-block rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Masuk</a>
            @endauth
        </div>
    @endif
@endsection

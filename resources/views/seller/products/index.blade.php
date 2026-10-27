@extends('layouts.app')

@section('title', 'Produk Toko')

@section('content')
    @include('seller.partials.nav')
    <h1 class="text-2xl font-bold">Produk {{ $store->name }}</h1>

    @if ($products->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <p class="text-gray-500">Belum ada produk di toko ini.</p>
            <a href="{{ route('seller.products.create') }}" class="mt-3 inline-block rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Tambah Produk</a>
        </div>
    @else
        <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Kategori</th>
                        <th class="px-4 py-3 font-medium">Harga</th>
                        <th class="px-4 py-3 font-medium">Stok</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $product->name }}</td>
                            <td class="px-4 py-3">{{ $product->category?->name ?? 'Tanpa kategori' }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $product->stock }}</td>
                            <td class="px-4 py-3">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="text-xs font-medium text-emerald-700 hover:underline">Ubah</a>
                                    <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
@endsection

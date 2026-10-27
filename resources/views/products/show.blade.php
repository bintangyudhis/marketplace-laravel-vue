@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <a href="{{ route('home') }}" class="text-sm font-medium text-emerald-700 hover:underline">Kembali ke etalase</a>

    <div class="mt-4 grid gap-8 rounded-2xl border border-gray-200 bg-white p-6 md:grid-cols-2">
        <div>
            @if ($product->imageUrl())
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-xl object-cover">
            @else
                <div class="flex aspect-square w-full items-center justify-center rounded-xl bg-gray-100 text-6xl font-bold text-gray-300">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
            @endif
        </div>
        <div>
            @if ($product->category)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $product->category->name }}</span>
            @endif
            <h1 class="mt-3 text-3xl font-bold">{{ $product->name }}</h1>
            <p class="mt-2 text-2xl font-bold text-emerald-700">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="mt-2 text-sm text-gray-500">Stok tersedia: {{ $product->stock }}</p>
            <p class="mt-1 text-sm text-gray-500">Toko: <span class="font-medium text-gray-700">{{ $product->store->name }}</span> ({{ $product->store->city }})</p>

            <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $product->description ?: 'Tidak ada deskripsi.' }}</div>

            @if ($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6 flex items-center gap-3">
                    @csrf
                    <label class="text-sm text-gray-600" for="quantity">Jumlah</label>
                    <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Tambah ke Keranjang</button>
                </form>
            @else
                <p class="mt-6 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700">Stok habis.</p>
            @endif
        </div>
    </div>
@endsection

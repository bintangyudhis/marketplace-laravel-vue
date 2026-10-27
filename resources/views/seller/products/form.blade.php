@extends('layouts.app')

@section('title', $product->exists ? 'Ubah Produk' : 'Tambah Produk')

@section('content')
    @include('seller.partials.nav')
    <div class="mx-auto max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold">{{ $product->exists ? 'Ubah Produk' : 'Tambah Produk' }}</h1>

        <form method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('seller.products.update', $product) : route('seller.products.store') }}" class="mt-5 space-y-4">
            @csrf
            @if ($product->exists) @method('PUT') @endif
            <div>
                <label class="mb-1 block text-sm font-medium" for="name">Nama produk</label>
                <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Tanpa kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium" for="price">Harga (Rp)</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="stock">Stok</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="image_url">URL foto produk (opsional)</label>
                <input id="image_url" type="url" name="image_url" value="{{ old('image_url', $product->image && str_starts_with($product->image, 'http') ? $product->image : '') }}" placeholder="https://contoh.com/foto.jpg" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('image_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="image_file">Atau unggah foto (maks 2 MB)</label>
                <input id="image_file" type="file" name="image_file" accept="image/*" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('image_file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                @if ($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="mt-2 h-24 w-24 rounded-lg object-cover">
                @endif
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) class="rounded border-gray-300"> Tampilkan di etalase
            </label>
            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Simpan Produk</button>
                <a href="{{ route('seller.products.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium">Batal</a>
            </div>
        </form>
    </div>
@endsection

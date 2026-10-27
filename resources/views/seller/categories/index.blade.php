@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    @include('seller.partials.nav')
    <h1 class="text-2xl font-bold">Kategori Produk</h1>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h2 class="font-bold">Tambah Kategori</h2>
            <form method="POST" action="{{ route('seller.categories.store') }}" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-medium" for="name">Nama kategori</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium" for="description">Deskripsi (opsional)</label>
                    <textarea id="description" name="description" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Tambah</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white lg:col-span-2">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-gray-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Jumlah produk</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $category->name }}</p>
                                <p class="text-xs text-gray-400">{{ $category->description }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $category->products_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('seller.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

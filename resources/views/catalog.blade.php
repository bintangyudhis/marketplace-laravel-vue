@extends('layouts.app')

@section('title', 'Etalase')

@section('content')
    <section class="mb-6">
        <h1 class="text-2xl font-bold">Etalase Produk</h1>
        <p class="mt-1 text-sm text-gray-500">Cari produk dari berbagai toko. Gunakan kolom pencarian atau filter kategori, hasilnya berubah langsung.</p>
    </section>

    <div id="catalog-app"></div>
    <noscript>
        <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">Aktifkan JavaScript untuk melihat katalog produk.</p>
    </noscript>
@endsection

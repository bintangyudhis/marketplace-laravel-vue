@extends('layouts.app')

@section('title', 'Profil Toko')

@section('content')
    @include('seller.partials.nav')
    <div class="mx-auto max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold">Profil Toko</h1>
        @include('seller.store.form', ['action' => route('seller.store.update'), 'method' => 'PUT', 'store' => $store, 'button' => 'Simpan Perubahan'])
    </div>
@endsection

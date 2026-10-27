@extends('layouts.app')

@section('title', 'Buka Toko')

@section('content')
    <div class="mx-auto max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold">Buka Toko</h1>
        <p class="mt-1 text-sm text-gray-500">Setiap akun dapat memiliki satu toko.</p>
        @include('seller.store.form', ['action' => route('seller.store.store'), 'method' => 'POST', 'store' => null, 'button' => 'Buat Toko'])
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold">Daftar Akun</h1>
        <p class="mt-1 text-sm text-gray-500">Satu akun untuk belanja sekaligus membuka toko.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" for="name">Nama lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password">Kata sandi</label>
                <input id="password" type="password" name="password" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password_confirmation">Ulangi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Daftar</button>
        </form>

        <p class="mt-4 text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-emerald-700 hover:underline">Masuk</a></p>
    </div>
@endsection

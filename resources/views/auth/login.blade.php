@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="mx-auto max-w-md rounded-2xl border border-gray-200 bg-white p-6">
        <h1 class="text-xl font-bold">Masuk</h1>
        <p class="mt-1 text-sm text-gray-500">Gunakan akun yang sudah terdaftar.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium" for="password">Kata sandi</label>
                <input id="password" type="password" name="password" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300"> Ingat saya
            </label>
            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Masuk</button>
        </form>

        <p class="mt-4 text-sm text-gray-500">Belum punya akun? <a href="{{ route('register') }}" class="font-medium text-emerald-700 hover:underline">Daftar</a></p>
    </div>
@endsection

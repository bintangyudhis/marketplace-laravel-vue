<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PasarKu') - PasarKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center gap-4 px-4 py-3">
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-emerald-700">PasarKu</a>
            <nav class="ml-2 hidden items-center gap-4 text-sm font-medium text-gray-600 md:flex">
                <a href="{{ route('home') }}" class="hover:text-emerald-700">Etalase</a>
                @auth
                    <a href="{{ route('orders.index') }}" class="hover:text-emerald-700">Pesanan Saya</a>
                    @if (auth()->user()->store)
                        <a href="{{ route('seller.products.index') }}" class="hover:text-emerald-700">Toko Saya</a>
                    @else
                        <a href="{{ route('seller.store.create') }}" class="hover:text-emerald-700">Buka Toko</a>
                    @endif
                @endauth
            </nav>
            <div class="ml-auto flex items-center gap-3">
                <a href="{{ route('cart.index') }}" class="relative rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium hover:border-emerald-600 hover:text-emerald-700">
                    Keranjang
                    @php($cartCount = app(\App\Services\CartService::class)->count())
                    <span id="cart-count" class="ml-1 inline-flex min-w-5 items-center justify-center rounded-full bg-emerald-600 px-1 text-xs font-semibold text-white">{{ $cartCount }}</span>
                </a>
                @auth
                    <span class="hidden text-sm text-gray-500 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-gray-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-gray-700">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg border border-emerald-600 px-3 py-1.5 text-sm font-medium text-emerald-700 hover:bg-emerald-50">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-6 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between">
            <p class="font-semibold text-gray-700">PasarKu</p>
            <p>Marketplace sederhana berbasis Laravel 12 dan Vue 3.</p>
        </div>
    </footer>
</body>
</html>

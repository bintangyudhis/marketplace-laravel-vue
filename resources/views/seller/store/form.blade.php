<form method="POST" action="{{ $action }}" class="mt-5 space-y-4">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div>
        <label class="mb-1 block text-sm font-medium" for="name">Nama toko</label>
        <input id="name" type="text" name="name" value="{{ old('name', $store?->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="about">Deskripsi toko</label>
        <textarea id="about" name="about" rows="3" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('about', $store?->about) }}</textarea>
        @error('about') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium" for="city">Kota</label>
            <input id="city" type="text" name="city" value="{{ old('city', $store?->city) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium" for="phone">Telepon (opsional)</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone', $store?->phone) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="address">Alamat (opsional)</label>
        <textarea id="address" name="address" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('address', $store?->address) }}</textarea>
        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium" for="postal_code">Kode pos (opsional)</label>
        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $store?->postal_code) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        @error('postal_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">{{ $button }}</button>
</form>

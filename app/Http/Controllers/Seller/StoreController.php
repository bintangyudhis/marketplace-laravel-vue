<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->store) {
            return redirect()->route('seller.store.edit');
        }

        return view('seller.store.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->store) {
            return redirect()->route('seller.store.edit');
        }

        $data = $this->validateStore($request);

        $request->user()->store()->create($data);

        return redirect()->route('seller.products.index')
            ->with('success', 'Toko berhasil dibuat. Sekarang tambahkan produk pertamamu.');
    }

    public function edit(Request $request): View
    {
        return view('seller.store.edit', ['store' => $this->ownStore($request)]);
    }

    public function update(Request $request): RedirectResponse
    {
        $store = $this->ownStore($request);
        $store->update($this->validateStore($request));

        return redirect()->route('seller.store.edit')->with('success', 'Data toko diperbarui.');
    }

    private function ownStore(Request $request): Store
    {
        $store = $request->user()->store;
        abort_unless($store, 404);

        return $store;
    }

    private function validateStore(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string', 'max:10'],
        ]);
    }
}

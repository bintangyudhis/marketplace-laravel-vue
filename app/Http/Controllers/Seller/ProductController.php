<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $store = $this->ownStore($request);

        $products = $store->products()->with('category')->latest()->paginate(10);

        return view('seller.products.index', ['store' => $store, 'products' => $products]);
    }

    public function create(Request $request): View
    {
        $store = $this->ownStore($request);

        return view('seller.products.form', [
            'store' => $store,
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $store = $this->ownStore($request);
        $data = $this->validateProduct($request);

        $data['slug'] = $this->uniqueSlug($store->id, $data['name']);
        $data['image'] = $this->resolveImage($request, $data);
        unset($data['image_url'], $data['image_file']);
        $data['is_active'] = $request->boolean('is_active');

        $store->products()->create($data);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Request $request, Product $product): View
    {
        $store = $this->ownStore($request);
        abort_unless($product->store_id === $store->id, 403);

        return view('seller.products.form', [
            'store' => $store,
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $store = $this->ownStore($request);
        abort_unless($product->store_id === $store->id, 403);

        $data = $this->validateProduct($request);

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($store->id, $data['name'], $product->id);
        }

        $data['image'] = $this->resolveImage($request, $data, $product->image);
        unset($data['image_url'], $data['image_file']);
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $store = $this->ownStore($request);
        abort_unless($product->store_id === $store->id, 403);

        if ($product->image && ! str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk dihapus.');
    }

    private function ownStore(Request $request)
    {
        $store = $request->user()->store;
        abort_unless($store, 404, 'Buat toko terlebih dahulu.');

        return $store;
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image_file' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function resolveImage(Request $request, array $data, ?string $current = null): ?string
    {
        if ($request->hasFile('image_file')) {
            return $request->file('image_file')->store('products', 'public');
        }

        if (! empty($data['image_url'])) {
            return $data['image_url'];
        }

        return $current;
    }

    private function uniqueSlug(string $storeId, string $name, ?string $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'produk';
        $slug = $base;
        $counter = 2;

        while (Product::where('store_id', $storeId)->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}

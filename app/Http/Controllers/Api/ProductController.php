<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar produk aktif untuk etalase Vue, mendukung pencarian teks
     * dan filter kategori (slug atau id).
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::active()
            ->with(['store:id,name,city', 'category:id,name,slug'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%'.$request->string('q').'%';
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('name', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $category = $request->string('category');
                $query->whereHas('category', function ($sub) use ($category) {
                    $sub->where('slug', $category)->orWhere('id', $category);
                });
            })
            ->latest()
            ->get()
            ->map(fn (Product $product) => $this->transform($product));

        return ResponseHelper::jsonResponse(true, 'Daftar produk berhasil diambil.', $products, 200);
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);
        $product->load(['store:id,name,city', 'category:id,name,slug']);

        return ResponseHelper::jsonResponse(true, 'Detail produk berhasil diambil.', $this->transform($product), 200);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::orderBy('name')
            ->withCount(['products' => fn ($query) => $query->active()])
            ->get(['id', 'name', 'slug', 'description']);

        return ResponseHelper::jsonResponse(true, 'Daftar kategori berhasil diambil.', $categories, 200);
    }

    private function transform(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'price_formatted' => 'Rp '.number_format($product->price, 0, ',', '.'),
            'stock' => $product->stock,
            'image_url' => $product->imageUrl(),
            'detail_url' => route('products.show', $product),
            'store' => $product->store ? [
                'id' => $product->store->id,
                'name' => $product->store->name,
                'city' => $product->store->city,
            ] : null,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
        ];
    }
}

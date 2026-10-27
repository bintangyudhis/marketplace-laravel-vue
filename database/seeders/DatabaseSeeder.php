<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi data contoh: kategori, satu user demo pemilik toko, satu toko,
     * satu user demo pembeli, dan produk contoh.
     *
     * Akun demo memakai kata sandi "password" dan hanya untuk mencoba
     * aplikasi di lingkungan lokal.
     */
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Elektronik', 'description' => 'Gawai dan perlengkapan elektronik.'],
            ['name' => 'Fashion', 'description' => 'Pakaian, sepatu, dan aksesori.'],
            ['name' => 'Makanan dan Minuman', 'description' => 'Makanan kemasan, kopi, dan camilan.'],
            ['name' => 'Rumah Tangga', 'description' => 'Perlengkapan rumah dan dapur.'],
        ])->mapWithKeys(function (array $item) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                ['name' => $item['name'], 'description' => $item['description']]
            );

            return [$item['name'] => $category];
        });

        $seller = User::updateOrCreate(
            ['email' => 'penjual@demo.test'],
            ['name' => 'Dewi Lestari', 'password' => 'password']
        );

        User::updateOrCreate(
            ['email' => 'pembeli@demo.test'],
            ['name' => 'Budi Santoso', 'password' => 'password']
        );

        $store = Store::updateOrCreate(
            ['user_id' => $seller->id],
            [
                'name' => 'Toko Berkah Jaya',
                'about' => 'Menjual kebutuhan harian, kopi pilihan, dan perlengkapan rumah dengan harga bersahabat.',
                'city' => 'Purwokerto',
                'phone' => '081200000000',
                'address' => 'Jalan Contoh No. 10, Purwokerto',
                'postal_code' => '53111',
                'is_verified' => true,
            ]
        );

        $products = [
            ['name' => 'Kopi Robusta Lampung 200 gram', 'category' => 'Makanan dan Minuman', 'price' => 42000, 'stock' => 40, 'description' => 'Biji kopi robusta sangrai medium dari Lampung, digiling saat pemesanan.'],
            ['name' => 'Teh Melati Celup Isi 25', 'category' => 'Makanan dan Minuman', 'price' => 12500, 'stock' => 120, 'description' => 'Teh melati celup aroma wangi untuk teman santai di rumah.'],
            ['name' => 'Keripik Singkong Balado 250 gram', 'category' => 'Makanan dan Minuman', 'price' => 18000, 'stock' => 75, 'description' => 'Keripik singkong renyah dengan bumbu balado pedas manis.'],
            ['name' => 'Headphone Bluetooth Lipat', 'category' => 'Elektronik', 'price' => 189000, 'stock' => 25, 'description' => 'Headphone bluetooth dengan bantalan empuk dan baterai hingga 20 jam.'],
            ['name' => 'Kabel Data USB-C Anyaman 1 meter', 'category' => 'Elektronik', 'price' => 35000, 'stock' => 200, 'description' => 'Kabel data USB-C anyaman nilon mendukung pengisian cepat.'],
            ['name' => 'Kaos Polos Katun Combed 24s', 'category' => 'Fashion', 'price' => 59000, 'stock' => 60, 'description' => 'Kaos polos katun combed 24s, adem dan tidak mudah melar.'],
            ['name' => 'Sepatu Lari Ringan Ukuran 42', 'category' => 'Fashion', 'price' => 249000, 'stock' => 12, 'description' => 'Sepatu lari ringan dengan sol empuk untuk lari harian.'],
            ['name' => 'Wajan Anti Lengket 24 cm', 'category' => 'Rumah Tangga', 'price' => 98000, 'stock' => 30, 'description' => 'Wajan anti lengket diameter 24 cm dengan pegangan tahan panas.'],
            ['name' => 'Botol Minum Stainless 750 ml', 'category' => 'Rumah Tangga', 'price' => 67000, 'stock' => 85, 'description' => 'Botol minum stainless menjaga suhu minuman hingga 8 jam.'],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['store_id' => $store->id, 'slug' => Str::slug($item['name'])],
                [
                    'category_id' => $categories[$item['category']]->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'description' => $item['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}

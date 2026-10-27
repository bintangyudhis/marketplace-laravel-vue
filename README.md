# Marketplace Laravel Vue

Kerangka awal aplikasi marketplace yang direncanakan memakai Laravel sebagai backend dan Vue.js sebagai frontend. Pengerjaan saat ini baru sampai fondasi data toko. Antarmuka marketplace dan integrasi Vue belum dibuat.

## Yang Sudah Ada

- Model dan migration tabel stores untuk data toko penjual
- Kolom toko meliputi nama, logo, deskripsi, telepon, kota, alamat, kode pos, dan status verifikasi
- Relasi toko ke pengguna dengan UUID sebagai kunci utama
- Helper respons JSON dengan format success, message, dan data
- Trait UUID untuk model
- Halaman sambutan bawaan Laravel pada route `/`

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- Blade
- Vite dan Tailwind CSS 4 untuk aset frontend
- SQLite sebagai database bawaan

## Cara Instalasi

Prasyarat:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm

Langkah instalasi:

```bash
git clone https://github.com/bintangyudhis/marketplace-laravel-vue.git
cd marketplace-laravel-vue
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Cara Menjalankan

Jalankan server pengembangan Laravel:

```bash
php artisan serve
```

Buka `http://localhost:8000` di browser untuk melihat halaman sambutan.

## Lisensi

Proyek ini memakai lisensi MIT. Lihat berkas [LICENSE](LICENSE) untuk teks lengkapnya.

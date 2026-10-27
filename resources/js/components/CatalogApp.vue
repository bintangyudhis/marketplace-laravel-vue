<script setup>
import { onMounted, ref, watch } from 'vue';
import axios from 'axios';

const products = ref([]);
const categories = ref([]);
const search = ref('');
const activeCategory = ref('');
const loading = ref(true);
const errorMessage = ref('');
const notice = ref('');
const addingId = ref(null);
let debounceTimer = null;

const rupiah = (value) => 'Rp ' + Number(value).toLocaleString('id-ID');

async function loadCategories() {
    try {
        const { data } = await axios.get('/api/categories');
        categories.value = data.data || [];
    } catch (error) {
        categories.value = [];
    }
}

async function loadProducts() {
    loading.value = true;
    errorMessage.value = '';
    try {
        const params = {};
        if (search.value.trim() !== '') params.q = search.value.trim();
        if (activeCategory.value !== '') params.category = activeCategory.value;
        const { data } = await axios.get('/api/products', { params });
        products.value = data.data || [];
    } catch (error) {
        errorMessage.value = 'Daftar produk gagal dimuat. Coba muat ulang halaman.';
        products.value = [];
    } finally {
        loading.value = false;
    }
}

async function addToCart(product) {
    addingId.value = product.id;
    notice.value = '';
    try {
        const { data } = await axios.post('/api/cart', { product_id: product.id, quantity: 1 });
        notice.value = data.message || 'Produk ditambahkan ke keranjang.';
        const badge = document.getElementById('cart-count');
        if (badge && data.data) badge.textContent = data.data.count;
    } catch (error) {
        notice.value = error.response?.data?.message || 'Produk gagal ditambahkan ke keranjang.';
    } finally {
        addingId.value = null;
    }
}

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadProducts, 350);
});

watch(activeCategory, loadProducts);

onMounted(async () => {
    await loadCategories();
    await loadProducts();
});
</script>

<template>
    <div>
        <div class="flex flex-col gap-3 md:flex-row md:items-center">
            <input
                v-model="search"
                type="search"
                placeholder="Cari produk, misalnya kopi atau sepatu"
                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-emerald-500 focus:outline-none"
            >
            <select
                v-model="activeCategory"
                class="rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm focus:border-emerald-500 focus:outline-none"
            >
                <option value="">Semua kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">
                    {{ category.name }}
                </option>
            </select>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            <button
                type="button"
                @click="activeCategory = ''"
                :class="activeCategory === '' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-300'"
                class="rounded-full border px-3 py-1 text-xs font-medium"
            >
                Semua
            </button>
            <button
                v-for="category in categories"
                :key="category.id"
                type="button"
                @click="activeCategory = category.slug"
                :class="activeCategory === category.slug ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-300'"
                class="rounded-full border px-3 py-1 text-xs font-medium"
            >
                {{ category.name }}
            </button>
        </div>

        <p v-if="notice" class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">{{ notice }}</p>
        <p v-if="errorMessage" class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-700">{{ errorMessage }}</p>

        <div v-if="loading" class="mt-10 text-center text-sm text-gray-400">Memuat produk...</div>

        <div v-else-if="products.length === 0" class="mt-6 rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <p class="text-gray-500">Tidak ada produk yang cocok dengan pencarian.</p>
        </div>

        <div v-else class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <article v-for="product in products" :key="product.id" class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white">
                <a :href="product.detail_url" class="block">
                    <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="aspect-square w-full object-cover">
                    <div v-else class="flex aspect-square w-full items-center justify-center bg-gray-100 text-5xl font-bold text-gray-300">
                        {{ product.name.charAt(0).toUpperCase() }}
                    </div>
                </a>
                <div class="flex flex-1 flex-col p-4">
                    <p v-if="product.category" class="text-xs font-medium text-emerald-700">{{ product.category.name }}</p>
                    <a :href="product.detail_url" class="mt-1 font-semibold leading-snug hover:text-emerald-700">{{ product.name }}</a>
                    <p class="mt-1 text-xs text-gray-400">{{ product.store ? product.store.name : '' }} - {{ product.store ? product.store.city : '' }}</p>
                    <p class="mt-2 font-bold text-emerald-700">{{ product.price_formatted || rupiah(product.price) }}</p>
                    <p class="text-xs text-gray-400">Stok {{ product.stock }}</p>
                    <button
                        type="button"
                        @click="addToCart(product)"
                        :disabled="product.stock < 1 || addingId === product.id"
                        class="mt-3 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-gray-300"
                    >
                        {{ product.stock < 1 ? 'Stok Habis' : (addingId === product.id ? 'Menambahkan...' : 'Tambah ke Keranjang') }}
                    </button>
                </div>
            </article>
        </div>
    </div>
</template>

import './bootstrap';
import { createApp } from 'vue';
import CatalogApp from './components/CatalogApp.vue';

const catalogEl = document.getElementById('catalog-app');

if (catalogEl) {
    createApp(CatalogApp).mount(catalogEl);
}

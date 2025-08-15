<template>
  <div class="p-4 container mx-auto">
    <button @click="sync" :disabled="loading" class="btn border p-3 rounded-md">
      {{ loading ? 'Syncing…' : 'Sync Shopify Products' }}
    </button>
    <p v-if="message" class="mt-2">{{ message }}</p>
    <h1 class="text-3xl font-medium py-2">Products</h1>
    <!-- Display products -->
    <div v-if="products.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">
      <div v-for="product in products" :key="product.shopify_id" class="border p-3 rounded-md">
        <img
          v-if="product.image_url"
          :src="product.image_url"
          :alt="product.image_alt || product.title"
          class="w-full h-48 object-cover mb-2 rounded"
        />
        <h3 class="font-semibold">{{ product.title }}</h3>
        <h3 class="font-semibold">$ {{ product.price }}</h3>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const loading = ref(false);
const message = ref('');
const products = ref([]); // store synced products

async function sync() {
  loading.value = true;
  message.value = '';
  try {
    const res = await fetch('/api/shopify/sync', {
      method: 'POST',
      // headers: { 'Accept': 'application/json' },
    });
    const data = await res.json();

    products.value = data.products || [];

    message.value = `Synced ${data.synced} products`;
  } catch (err) {
    message.value = err.message || 'Sync failed';
  } finally {
    loading.value = false;
  }
}
</script>

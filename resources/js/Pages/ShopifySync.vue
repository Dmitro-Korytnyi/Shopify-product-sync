<template>
  <div class="p-4">
    <button @click="sync" :disabled="loading" class="btn border p-3 rounded-md">
      {{ loading ? 'Syncing…' : 'Sync Shopify Products' }}
    </button>
    <p v-if="message" class="mt-2">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const loading = ref(false);
const message = ref('');
async function sync(){
  loading.value = true;
  message.value = '';
  try {
    const res = await fetch('/api/shopify/sync', {
      method: 'POST',
      // headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
    });
    const data = await res.json();
    message.value = `Synced ${data.synced} products`;
  } catch(err) {
    message.value = err.message || 'Sync failed';
  } finally {
    loading.value = false;
  }
}
</script>

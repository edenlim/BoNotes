<script setup>
import { ref } from 'vue';
import AccountModal from '../overlay/account/AccountModal.vue';

const isModalOpen = ref(false);
</script>

<template>
  <div
    @click="isModalOpen = true"
    class="ml-auto w-fit p-2 border-2 rounded-xl cursor-pointer flex items-center select-none text-center"
  >
    Konto ▼
  </div>

  <AccountModal
    v-if="isModalOpen"
    @close="isModalOpen = false"
  />
</template>

<style scoped>

</style>

<script setup>
import { ref } from 'vue';
import AccountModal from '../overlay/account/AccountModal.vue';

const isModalOpen = ref(false);
</script>

<template>
  <div
      v-bind="$attrs"
      @click="isModalOpen = true"
      class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
  >
<!--    Konto ▼-->
      Konto
  </div>

  <AccountModal
      v-if="isModalOpen"
      @close="isModalOpen = false"
  />
</template>

<style scoped>

</style>

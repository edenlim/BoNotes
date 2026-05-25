<script setup>
import { ref } from 'vue';
import AccountModal from '../overlay/account/AccountModal.vue';

const props = defineProps({
    isLoggedIn: { type: Boolean, required: true }
});
const emit = defineEmits(['login-success', 'logout-success']);
const isModalOpen = ref(false);
const handleLogout = () => {
    document.cookie = "current_login_session=; path=/; max-age=0; SameSite=Strict; Secure";
    console.log('Logged out')
};
</script>

<template>
  <div
      v-if="isLoggedIn"
      @click="handleLogout(); emit('logout-success')"
      class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
  >
      Konto
  </div>


  <div
      v-else
      v-bind="$attrs"
      @click="isModalOpen = true"
      class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
  >
<!--    Konto ▼-->
      Login
  </div>

  <AccountModal
      v-if="isModalOpen"
      @close="isModalOpen = false"
      @login-success="emit('login-success')"
  />
</template>

<style scoped>

</style>

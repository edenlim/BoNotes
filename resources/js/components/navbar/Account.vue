<script setup>
import { ref } from 'vue';
import AccountModal from '../overlay/account/AccountModal.vue';

const props = defineProps({
    isLoggedIn: { type: Boolean, required: true }
});
const emit = defineEmits(['login-success', 'logout-success']);
const isModalOpen = ref(false);
const accountDropdownShow = ref(false);

const handleLogout = () => {
    document.cookie = "current_login_session=; path=/; max-age=0; SameSite=Strict; Secure";
    accountDropdownShow.value = false;
    console.log('Logged out')
};

const toggleDropdown = () =>{
    accountDropdownShow.value = !accountDropdownShow.value;
}
</script>

<template>
    <div v-if="isLoggedIn">
        <div
            @click="toggleDropdown"
            class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
        >
            Konto
        </div>
        <div
            class="flex-col absolute right-0 bg-white gap-4 p-4 rounded-b-xl shadow-[0_4px_6px_-2px_rgba(0,0,0,0.1)] z-49"
            :class="accountDropdownShow ? 'flex' : 'hidden'"
        >
            <div class="hover-pop">
                Einstellungen
            </div>
            <div
                class="hover-pop"
                @click="handleLogout(); emit('logout-success')"
            >
                Ausloggen
            </div>
        </div>
    </div>

    <div
      v-else
      v-bind="$attrs"
      @click="isModalOpen = true"
      class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
    >
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

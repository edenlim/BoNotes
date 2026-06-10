<script setup>
import { computed, ref } from 'vue';
import Cookies from 'js-cookie'; // Imported to grab the CSRF token
import AccountModal from '../overlay/account/AccountModal.vue';
import EditAccountModal from "../overlay/account/EditAccountModal.vue";
import Background from "../overlay/Background.vue";

const props = defineProps({
    isLoggedIn: { type: Boolean, required: true }
});
const emit = defineEmits(['login-success', 'logout-success']);

const isLoginModalOpen = ref(false);
const isEditModalOpen = ref(false);
const accountDropdownShow = ref(false);
const showOverlay = computed(() => isEditModalOpen.value !== false || isLoginModalOpen.value !== false);

const handleLogout = async () => {
    try {
        // Send the secure POST request to your Laravel backend
        const response = await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN') // Attach CSRF token
            },
            credentials: 'include' // Ensure session cookies are sent
        });

        // Only update the UI if the backend successfully destroyed the session
        if (response.ok) {
            accountDropdownShow.value = false;
            emit('logout-success');
        } else {
            console.error("Logout failed on the server.");
        }
    } catch (error) {
        console.error("Network error during logout:", error);
    }
};

const toggleDropdown = () => {
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
            <div
                class="hover-pop"
                @click="isEditModalOpen = true"
            >
                Einstellungen
            </div>
            <div
                class="hover-pop"
                @click="handleLogout"
            >
                Ausloggen
            </div>
        </div>
    </div>

    <div
        v-else
        v-bind="$attrs"
        @click="isLoginModalOpen = true"
        class="w-full p-2 border-2 rounded-xl cursor-pointer flex justify-center items-center select-none hover-pop"
    >
        Login
    </div>

    <Background @click="isLoginModalOpen = false; isEditModalOpen = false" :show="showOverlay">
        <AccountModal
            v-if="isLoginModalOpen"
            @close="isLoginModalOpen = false"
            @login-success="emit('login-success')"
        />
        <EditAccountModal
            v-if="isEditModalOpen"
            @close="isEditModalOpen = false"
        />
    </Background>

</template>

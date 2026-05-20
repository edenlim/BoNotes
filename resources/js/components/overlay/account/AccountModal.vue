<script setup>
import { ref } from 'vue';
import LoginForm from './LoginForm.vue';
import RegisterForm from './RegisterForm.vue';
import CloseButton from "../../../templates/CloseButton.vue";

const emit = defineEmits(['close']);

const activeView = ref('login');
</script>

<template>
    <div
        class="fixed inset-0 w-screen h-screen bg-black/40 flex justify-center items-center z-50"
        @click="emit('close')"
    >
        <div
            class="bg-white p-8 rounded-2xl w-full max-w-[400px] relative shadow-lg"
            @click.stop
        >
            <div class="flex items-center justify-end p-2">
                <CloseButton @close="emit('close')" />
            </div>

            <LoginForm
                v-if="activeView === 'login'"
                @switch-to-register="activeView = 'register'"
                @login-success="emit('close')"
            />

            <RegisterForm
                v-else
                @switch-to-login="activeView = 'login'"
                @register-success="emit('close')"
            />

        </div> </div>
</template>

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
            class="p-8 rounded-2xl w-full max-w-[400px] relative shadow-lg"
            style="background-color: var(--color-secondary);"
            @click.stop
        >
            <div class="flex items-center justify-between -mt-1">
            <span class="form-brand-svg"></span>

                <CloseButton
                    @close="emit('close')"
                    backgroundColor="var(--color-primary)"
                    x-color="var(--color-secondary)"
                />
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

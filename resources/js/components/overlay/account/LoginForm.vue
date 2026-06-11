<script setup>
import { ref } from 'vue';
import Cookies from 'js-cookie';

const emit = defineEmits(['switch-to-register', 'login-success']);

const email = ref('');
const password = ref('');
const errorMessage = ref('');

const handleLogin = async () => {
    errorMessage.value = '';

    try {
        await fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        });

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN')
            },
            credentials: 'include',
            body: JSON.stringify({
                email: email.value,
                password: password.value
            })
        });

        if (response.ok) {
            emit('login-success');
        } else {
            const errorData = await response.json();
            errorMessage.value = errorData.message || 'E-Mail oder Passwort ist falsch.';
        }

    } catch (error) {
        console.error("Login failed:", error);
        errorMessage.value = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
    }
};
</script>
<template>
    <div class="flex flex-col">

        <h2 class="form-title">Einloggen</h2>
        <p class="form-subtitle" >Log dich bei BoNotes ein!</p>

        <form @submit.prevent="handleLogin" class="flex flex-col gap-1">

            <div class="form-input-group">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" id="email" v-model="email" autocomplete="current-email" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" id="password" v-model="password" autocomplete="current-password" required class="form-input-field" />
            </div>

            <p v-if="errorMessage" class="form-error-text">{{ errorMessage }}</p>

            <p class="form-subtitle">
                Kein Konto? Melde dich
                <span class="form-link-button" @click="emit('switch-to-register')">hier</span>
                an
            </p>

            <div class="flex justify-center mt-4">
                <button type="submit" class="form-submit-button">Einloggen</button>
            </div>

        </form>
    </div>
</template>

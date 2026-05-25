<script setup>
import { ref } from 'vue';

const emit = defineEmits(['switch-to-register', 'login-success']);

const email = ref('');
const password = ref('');
const errorMessage = ref('');

const handleLogin = async () => {
    errorMessage.value = '';
    try {
        const response = await fetch('/mock/user.json');
        if (!response.ok) {
            throw new Error('Netzwerk-Fehler beim Laden der Benutzerdaten.');
        }

        const userlist = await response.json();

        const matchedUser = userlist.find(
            user => user.email === email.value && user.password === password.value
        );

        if (matchedUser) {
            console.log("Login erfolgreich für:", matchedUser.name);

            const sessionData = {
                username: matchedUser.name,
                userid: matchedUser.id
            };

            const cookieValue = encodeURIComponent(JSON.stringify(sessionData));

            document.cookie = `current_login_session=${cookieValue}; path=/; max-age=86400; SameSite=Strict; Secure`;

            emit('login-success');
        } else {
            errorMessage.value = 'E-Mail oder Passwort ist falsch.';
        }

    } catch (error) {
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
                <input type="email" id="email" v-model="email" required class="form-input-field" />
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

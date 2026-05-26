<script setup>
import { ref } from 'vue';
import Cookies from 'js-cookie';

const emit = defineEmits(['switch-to-login', 'login-success']);

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirm = ref('');
const errorMessage = ref('');

const handleRegister = async () => {
    errorMessage.value = '';

    if (password.value !== passwordConfirm.value) {
        errorMessage.value = 'Die Passwörter stimmen nicht überein.';
        return;
    }

    try {
        const response = await fetch('/mock/user.json');
        if (!response.ok) {
            throw new Error('Netzwerk-Fehler beim Laden der Benutzerdaten.');
        }
        const userlist = await response.json();

        const emailExists = userlist.some(
            user => user.email === email.value
        );

        if (emailExists) {
            errorMessage.value = "User existiert schon";
        } else {
            console.log("Register success");

            const nextUserId = userlist.length > 0
                ? userlist[userlist.length - 1].id + 1
                : 1;

            const sessionData = {
                username: name.value,
                email: email.value,
                userid: nextUserId
            };

            Cookies.set('current_login_session', updatedSessionData, {
                expires: 1,
                path: '/',
                sameSite: 'Strict',
                secure: true
            });

            emit('login-success');
        }

    } catch (error) {
        errorMessage.value = 'Fehler bei der Registrierung. Bitte versuche es erneut.';
    }
};
</script>
<template>
    <div class="flex flex-col">


        <h2 class="form-title">Konto erstellen</h2>
        <p class="form-subtitle">Tritt der BoNotes Community bei</p>

        <form @submit.prevent="handleRegister" class="flex flex-col gap-1">

            <div class="form-input-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" v-model="name" autocomplete="new-name" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" id="email" v-model="email" autocomplete="new-email" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" id="password" v-model="password" autocomplete="new-password" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="passwordConfirm" class="form-label">Passwort bestätigen</label>
                <input type="password" id="passwordConfirm" v-model="passwordConfirm" autocomplete="new-password" required class="form-input-field" />
            </div>

            <p v-if="errorMessage" class="form-error-text">{{ errorMessage }}</p>

            <p class="form-subtitle">
                Du hast bereits ein Konto? Logge dich
                <span class="form-link-button" @click="emit('switch-to-login')">hier</span>
                ein
            </p>

            <div class="flex justify-center mt-4">
                <button type="submit" class="form-submit-button">Registrieren</button>
            </div>

        </form>
    </div>
</template>

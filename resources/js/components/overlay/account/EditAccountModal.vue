<script setup>
import { ref } from 'vue';
import Cookies from 'js-cookie';
import CloseButton from "../../../templates/CloseButton.vue";

const emit = defineEmits(['close']);

const session = ref(null);
const cookieData = Cookies.get('current_login_session');
session.value = cookieData ? JSON.parse(cookieData) : null;

const name = ref(session.value ? session.value.username : '');
const email = ref(session.value ? session.value.email : '');

const password = ref('');
const passwordConfirm = ref('');
const currentPassword = ref('');
const errorMessage = ref('');

const handleRegister = async () => {
    errorMessage.value = '';

    if (password.value !== passwordConfirm.value) {
        errorMessage.value = 'Die neuen Passwörter stimmen nicht überein.';
        return;
    }

    try {
        const response = await fetch('/mock/user.json');
        if (!response.ok) {
            throw new Error('Netzwerk-Fehler beim Laden der Benutzerdaten.');
        }
        const userlist = await response.json();

        const currentUser = userlist.find(user => user.id === session.value?.userid);

        if (!currentUser || currentUser.password !== currentPassword.value) {
            errorMessage.value = 'Das aktuelle Passwort ist falsch.';
            return;
        }

        console.log("Profile update success");

        const updatedSessionData = {
            username: name.value,
            email: email.value,
            userid: session.value.userid
        };

        Cookies.set('current_login_session', JSON.stringify(updatedSessionData), {
            expires: 1,
            path: '/',
            sameSite: 'Strict',
            secure: true
        });

        emit('close');

    } catch (error) {
        errorMessage.value = 'Fehler beim Speichern der Änderungen.';
    }
};
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

            <div class="flex flex-col">
                <h2 class="form-title">Einstellungen</h2>

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
                        <label for="password" class="form-label">Neues Passwort</label>
                        <input type="password" id="password" v-model="password" autocomplete="new-password" required class="form-input-field" />
                    </div>

                    <div class="form-input-group">
                        <label for="passwordConfirm" class="form-label">Neues Passwort bestätigen</label>
                        <input type="password" id="passwordConfirm" v-model="passwordConfirm" autocomplete="new-password" required class="form-input-field" />
                    </div>

                    <p class="form-subtitle">
                        Bitte bestätige deine Identität mit deinem aktuellen Passwort.
                    </p>

                    <div class="form-input-group">
                        <label for="currentPassword" class="form-label">Aktuelles Passwort</label>
                        <input type="password" id="currentPassword" v-model="currentPassword" autocomplete="current-password" required class="form-input-field" />
                    </div>

                    <p v-if="errorMessage" class="form-error-text">{{ errorMessage }}</p>

                    <div class="flex justify-center mt-4">
                        <button type="submit" class="form-submit-button">Speichern</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

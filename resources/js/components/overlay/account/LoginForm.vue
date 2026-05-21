<script setup>
import { ref } from 'vue';

// Definiere die Events, die wir an AccountModal.vue senden können
const emit = defineEmits(['switch-to-register', 'login-success']);

// Reaktive Variablen für die Eingabefelder
const email = ref('');
const password = ref('');
const errorMessage = ref(''); // Für Fehler vom Backend (Laravel)

// Die Funktion, die beim Klick auf "Einloggen" aufgerufen wird
const handleLogin = async () => {
  errorMessage.value = ''; // Vorherige Fehler zurücksetzen

  try {
    // HIER kommt später dein API-Aufruf an Laravel hin (z.B. mit Axios)
    // const response = await axios.post('/api/login', {
    //   email: email.value,
    //   password: password.value
    // });

    // Für jetzt simulieren wir einfach einen erfolgreichen Klick:
    console.log("Sende an Laravel:", email.value, password.value);

    // Sag dem Modal, dass der Login erfolgreich war und es sich schließen kann
    emit('login-success');

  } catch (error) {
    // Wenn Laravel einen Fehler wirft (z.B. falsches Passwort)
    // errorMessage.value = error.response.data.message;
    errorMessage.value = 'E-Mail oder Passwort ist falsch.';
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
                <input type="password" id="password" v-model="password" required class="form-input-field" />
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

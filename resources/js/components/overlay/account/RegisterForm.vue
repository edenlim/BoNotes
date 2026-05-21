<script setup>
import { ref } from 'vue';

// Definiere die Events, die wir an AccountModal.vue senden können
const emit = defineEmits(['switch-to-login', 'register-success']);

// Reaktive Variablen für die Eingabefelder
const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirm = ref('');
const errorMessage = ref('');

// Die Funktion, die beim Klick auf "Registrieren" aufgerufen wird
const handleRegister = async () => {
  errorMessage.value = ''; // Vorherige Fehler zurücksetzen

  // 1. Prüfen, ob die Passwörter identisch sind
  if (password.value !== passwordConfirm.value) {
    errorMessage.value = 'Die Passwörter stimmen nicht überein.';
    return; // Bricht die Funktion hier ab
  }

  try {
    // HIER kommt später dein API-Aufruf an Laravel hin
    // const response = await axios.post('/api/register', {
    //   name: name.value,
    //   email: email.value,
    //   password: password.value,
    //   password_confirmation: passwordConfirm.value
    // });

    // Falls Passwörter übereinastimmen
    console.log("Registriere neuen Nutzer:", name.value, email.value);

    // Sag dem Modal, dass die Registrierung erfolgreich war und es schließen kann
    emit('register-success');

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
                <input type="text" id="name" v-model="name" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" id="email" v-model="email" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" id="password" v-model="password" required class="form-input-field" />
            </div>

            <div class="form-input-group">
                <label for="passwordConfirm" class="form-label">Passwort bestätigen</label>
                <input type="password" id="passwordConfirm" v-model="passwordConfirm" required class="form-input-field" />
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

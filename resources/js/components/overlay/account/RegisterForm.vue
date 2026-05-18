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
    
    // Für jetzt simulieren wir einen Erfolg:
    console.log("Registriere neuen Nutzer:", name.value, email.value);
    
    // Sag dem Modal, dass die Registrierung erfolgreich war und es schließen kann
    emit('register-success');
    
  } catch (error) {
    errorMessage.value = 'Fehler bei der Registrierung. Bitte versuche es erneut.'; 
  }
};
</script>

<template>
  <div class="register-form-container">
    
    <div class="brand-header">
      <span class="brand-text">BoNotes</span>
    </div>

    <h2 class="title">Konto erstellen</h2>
    <p class="subtitle">Tritt der BoNotes Community bei</p>

    <form @submit.prevent="handleRegister" class="form-wrapper">
      
      <div class="input-group">
        <label for="name">Name</label>
        <input 
          type="text" 
          id="name" 
          v-model="name" 
          required 
          class="input-field"
        />
      </div>

      <div class="input-group">
        <label for="email">E-Mail</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          required 
          class="input-field"
        />
      </div>

      <div class="input-group">
        <label for="password">Passwort</label>
        <input 
          type="password" 
          id="password" 
          v-model="password" 
          required 
          class="input-field"
        />
      </div>

      <div class="input-group">
        <label for="passwordConfirm">Passwort bestätigen</label>
        <input 
          type="password" 
          id="passwordConfirm" 
          v-model="passwordConfirm" 
          required 
          class="input-field"
        />
      </div>

      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

      <p class="switch-text">
        Du hast bereits ein Konto? Logge dich 
        <span class="link-button" @click="emit('switch-to-login')">hier</span> 
        ein
      </p>

      <div class="button-wrapper">
        <button type="submit" class="submit-button">Registrieren</button>
      </div>
      
    </form>
  </div>
</template>

<style scoped>
/* Die gleichen Styles wie im LoginForm für ein einheitliches Design */
.register-form-container {
  display: flex;
  flex-direction: column;
}

.brand-header {
  margin-bottom: 0.5rem;
}

.brand-text {
  font-weight: bold;
  color: #6b21a8; 
  font-size: 1.1rem;
}

.title {
  font-size: 1.4rem;
  font-weight: bold;
  margin-bottom: 0.2rem;
}

.subtitle {
  color: #666;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
}

.form-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1rem; 
}

.input-group {
  display: flex;
  flex-direction: column;
  text-align: left;
}

.input-group label {
  font-size: 0.85rem;
  color: #444;
  margin-bottom: 0.3rem;
}

.input-field {
  padding: 0.6rem;
  border: 1px solid #ccc;
  border-radius: 0.4rem;
  font-size: 1rem;
  outline: none;
}

.input-field:focus {
  border-color: #007bff; 
}

.switch-text {
  font-size: 0.9rem;
  color: #555;
  margin-top: 0.2rem;
}

.link-button {
  color: #0056b3; 
  font-weight: bold;
  text-decoration: underline;
  cursor: pointer;
}

.button-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 1rem;
}

.submit-button {
  padding: 0.5rem 1.5rem;
  background-color: white;
  border: 1px solid #333;
  border-radius: 0.5rem;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #f8f9fa;
}

.error-text {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: -0.5rem;
}
</style>
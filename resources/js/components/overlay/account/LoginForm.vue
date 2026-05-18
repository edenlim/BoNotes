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
  <div class="login-form-container">
    
    <div class="brand-header">
      <span class="brand-text">BoNotes</span>
    </div>

    <h2 class="title">Einloggen</h2>
    <p class="subtitle">Log dich bei BoNotes ein!</p>

    <form @submit.prevent="handleLogin" class="form-wrapper">
      
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

      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

      <p class="switch-text">
        Kein Konto? Melde dich 
        <span class="link-button" @click="emit('switch-to-register')">hier</span> 
        an
      </p>

      <div class="button-wrapper">
        <button type="submit" class="submit-button">Einloggen</button>
      </div>
      
    </form>
  </div>
</template>

<style scoped>
/* Das CSS ist angelehnt an das Design aus deinen Screenshots */
.login-form-container {
  display: flex;
  flex-direction: column;
}

.brand-header {
  margin-bottom: 0.5rem;
}

.brand-text {
  font-weight: bold;
  color: #6b21a8; /* BoNotes Lila-Ton */
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
  gap: 1rem; /* Abstand zwischen den Input-Feldern */
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
  border-color: #007bff; /* Rahmenfarbe bei Klick in das Feld */
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
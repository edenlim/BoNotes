<script setup>
import { ref } from 'vue';
import LoginForm from './LoginForm.vue';
import RegisterForm from './RegisterForm.vue';

const emit = defineEmits(['close']);

const activeView = ref('login');
</script>

<template>
  <div 
    class="modal-overlay" 
    @click="emit('close')"
  >
    
    <div class="modal-content" @click.stop>
      
      <button class="close-button" @click="emit('close')">
        ✕
      </button>

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

    </div>
  </div>
</template>

<style scoped>
/* CSS */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.4); /* Dunkler, halbtransparenter Hintergrund */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50; /* Stellt sicher, dass es über allem anderen liegt */
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 1rem; /* Abgerundete Ecken */
  width: 100%;
  max-width: 400px;
  position: relative; /* Wichtig für den X-Button */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.close-button {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: #666;
}

.close-button:hover {
  color: #000;
}
</style>
import Alpine from 'alpinejs';
import { createApp } from 'vue';
import AppRoot from './components/App.vue';

window.Alpine = Alpine;
Alpine.start();

// Mount AppRoot DIRECTLY as the root component
const app = createApp(AppRoot);

if (document.getElementById('vue-root')) {
    app.mount('#vue-root');
}

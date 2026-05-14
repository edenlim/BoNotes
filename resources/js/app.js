import Alpine from 'alpinejs';
import { createApp } from 'vue';
import Root from './layout/Root.vue';

window.Alpine = Alpine;
Alpine.start();

// Mount AppRoot DIRECTLY as the root component
const root = createApp(Root);

if (document.getElementById('vue-root')) {
    root.mount('#vue-root');
}

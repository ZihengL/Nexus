/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'
import { createApp, ref } from 'vue';

// Components
import App from './App.vue';

// Components
const app = createApp(App)

// Utilisez app.config.globalProperties pour définir une propriété globale
app.config.globalProperties.$isConnected = ref(false);

// Enregistrez les plugins
registerPlugins(app)

// Montez l'application
app.mount('#app')
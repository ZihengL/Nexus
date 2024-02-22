/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'
import { createApp } from 'vue'
import App from './App.vue';

// Components
const app = createApp(App)

// Utilisez app.config.globalProperties pour définir une propriété globale

// Ajoutez une variable globale boolean (par exemple, isGlobalVariable)
app.config.globalProperties.$isGlobalVariable = false;
//app.config.globalProperties.$isConnected = false;
//Vue.prototype.$isConnected = false
// Enregistrez vos plugins
registerPlugins(app);

// Montez l'application
app.mount('#app')
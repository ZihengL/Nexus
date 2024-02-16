/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'

// Components
import App from './App.vue';
//import { Vue }  from 'vue';
//import { isConnected } from './JS/GlobalVar';

// Composables
import { createApp } from 'vue'


const app = createApp(App)

registerPlugins(app)
//Vue.prototype.$isConnected = isConnected

app.mount('#app')

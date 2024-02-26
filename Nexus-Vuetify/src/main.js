/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'
import { createApp, ref, provide } from 'vue';

// Components
import App from "./App.vue";
//import { Vue }  from 'vue';
//import { isConnected } from './JS/GlobalVar';

// Composables
import { createApp } from "vue";

// Utilisez app.config.globalProperties pour définir une propriété globale
app.config.globalProperties.$isConnected = ref(false);

// Création de l'état global isConnected
/*const isConnected = ref(false);
// Méthode pour basculer l'état
const toggleConnection = () => {
  isConnected.value = !isConnected.value;
};

// Fournir l'état et la méthode aux composants descendants
app.provide('isConnected', isConnected);
app.provide('toggleConnection', toggleConnection);*/

registerPlugins(app);


app.mount("#app");

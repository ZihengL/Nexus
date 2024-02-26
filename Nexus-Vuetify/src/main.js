// main.js

// Import Vue and Vuetify plugins
import { createApp, ref, provide } from 'vue';
import { registerPlugins } from '@/plugins';

// Import App component
import App from './App.vue';

// Import Firebase functionalities
import { initializeApp } from 'firebase/app';
import { getStorage } from 'firebase/storage';

// Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyC2l5gdjn5nRUPEYWZwUnbJM1Fyr0aL2IY",
  authDomain: "nexus-414517.firebaseapp.com",
  projectId: "nexus-414517",
  storageBucket: "nexus-414517.appspot.com",
  messagingSenderId: "155427684847",
  appId: "G-4EGMVSZE3G",
};

// Initialize Firebase
initializeApp(firebaseConfig);

// Now that Firebase is initialized, you can access Firebase Storage
// or other Firebase services where needed in your app
const storage = getStorage();

// Create Vue app
const app = createApp(App)

// Utilisez app.config.globalProperties pour définir une propriété globale
app.config.globalProperties.$isConnected = ref(false);

// Création de l'état global isConnected
const isConnected = ref(false);
// Méthode pour basculer l'état
const toggleConnection = () => {
  isConnected.value = !isConnected.value;
};

// Provide the state and method to descendant components
app.provide('isConnected', isConnected);
app.provide('toggleConnection', toggleConnection);*/

// Register Vuetify and other plugins
registerPlugins(app)

// Mount the Vue application
app.mount('#app')

// Optionally, export storage if you need to use it across components
export { storage };

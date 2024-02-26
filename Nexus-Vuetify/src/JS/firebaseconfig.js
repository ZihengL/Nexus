// src/firebaseConfig.js
import { initializeApp } from 'firebase/app';
import { getStorage } from 'firebase/storage';

// Your web app's Firebase configuration (you can find this in your Firebase console)
const firebaseConfig = {
  apiKey: "AIzaSyC2l5gdjn5nRUPEYWZwUnbJM1Fyr0aL2IY",
  authDomain: "nexus-414517.firebaseapp.com",
  projectId: "nexus-414517",
  storageBucket: "nexus-414517.appspot.com",
  messagingSenderId: "155427684847",
  appId: "G-4EGMVSZE3G",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase Storage
const storage = getStorage(app);

export { storage };

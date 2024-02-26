// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyC2l5gdjn5nRUPEYWZwUnbJM1Fyr0aL2IY",
  authDomain: "nexus-414517.firebaseapp.com",
  projectId: "nexus-414517",
  storageBucket: "nexus-414517.appspot.com",
  messagingSenderId: "155427684847",
  appId: "1:155427684847:web:648c80a7e9b2147443361a",
  measurementId: "G-4EGMVSZE3G",
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);
const analytics = getAnalytics(firebaseApp);

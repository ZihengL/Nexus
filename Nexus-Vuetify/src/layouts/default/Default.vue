<template>
  <v-app class="app">
    <div class="all">
      <NavBar class="leNav glass" :connected="connect" />
      <div class="contentPage">
        <DefaultView class="vue" @changeVal="updateConnect" :connected="connect" />
        <Footer :connected="connect"></Footer>
      </div>
    </div>
  </v-app>
</template>

<script setup>
import storageManager from "../../JS/localStorageManager"
import NavBar from '../../components/NavBar.vue';
import Footer from '../../components/Footer.vue';
import DefaultView from './View.vue';
import { ref, onMounted } from 'vue';

let connect = ref(false);

const initializeConnect = () => {
  // localStorage.setItem('isConnected', false)
  // storageManager.setIsConnected(false)
  const storedIsConnected = storageManager.getIsConnected()
  //const storedIsConnected =  localStorage.getItem('isConnected');
  if (storedIsConnected !== null) {
    connect.value = storedIsConnected;
    // console.log("Default.vue  storageManager.getIsConnected() : ", storageManager.getIsConnected());
    // console.log("Default.vue connect.value", connect.value)
  } else {
    // localStorage.setItem('isConnected', connect.value);
    storageManager.setIsConnected(connect.value)
    // console.log("Default.vue  storageManager.getIsConnected()NULL : ", storageManager.getIsConnected());
  }
 
};

onMounted(() => {
  initializeConnect();
});

const updateConnect = () => {
  connect.value = !connect.value;
  // localStorage.setItem('isConnected', connect.value);
  storageManager.setIsConnected(connect.value)
  //console.log('le changement : ', storageManager.getIsConnected());
};
</script>

<style src="../../styles/settings.scss"></style>

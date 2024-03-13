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
  // storageManager.setIsConnected(false)
  const storedIsConnected = storageManager.getIsConnected()
  if (storedIsConnected !== null) {
    connect.value = storedIsConnected;
  } else {
    storageManager.setIsConnected(connect.value)
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

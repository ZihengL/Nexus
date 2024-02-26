<template>
  <v-app class="app">
    <div class="all">
      <NavBar class="leNav glass" :connected="connect"/>
      <div class="contentPage">
        <default-view  class="vue" @changeVal="updateConnect" :connected="connect"/>
        <Footer :connected="connect"/>
      </div>
    </div>
  </v-app>
</template>

<script setup>
import NavBar from '../../components/NavBar.vue';
import Footer from '../../components/Footer.vue';
import DefaultView from './View.vue'
import { ref } from 'vue';

let connect = ref(false);

const updateConnect = () => {
  connect.value = !connect.value;
  console.log('le changement : ', connect.value)
};
</script>

<style src="../../styles//settings.scss"></style>
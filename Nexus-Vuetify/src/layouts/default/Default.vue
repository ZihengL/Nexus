<template>
  <v-app>
    <NavBar :isConnected="test"/>
    <default-view  style="margin-top: 5%;"/>
    <Footer/>
  </v-app>
</template>

<script setup>
  import NavBar from '/src/components/NavBar.vue';
  import Footer from '/src/components/Footer.vue';
  import DefaultView from './View.vue'
  var test = 'bla bla';
</script>

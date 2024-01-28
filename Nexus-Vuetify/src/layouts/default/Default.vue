<template>
  <v-app>
    <NavBar />
    <Homecarousel/>

    <default-view  style="margin-top: 5%;"/>
  </v-app>
</template>

<script setup>
  import NavBar from '/src/components/NavBar.vue';
  //import DefaultBar from './AppBar.vue'
  import DefaultView from './View.vue'
  import Homecarousel from '/src/components/Homecarousel.vue'
</script>

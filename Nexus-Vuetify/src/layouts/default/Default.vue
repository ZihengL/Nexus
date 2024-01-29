<template>
  <v-app>
    <NavBar :isConnected="test"/>
    <default-view  style="margin-top: 5%;"/>
  </v-app>
</template>

<script setup>
  import NavBar from '/src/components/NavBar.vue';
  import HomeCarousel from '/src/components/HomeCarousel.vue';
  //import DefaultBar from './AppBar.vue'
  import DefaultView from './View.vue'
  var test = 'bla bla';
</script>

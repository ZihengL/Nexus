<template>
  <v-app>
    <NavBar />
    <HomeCarousel/>
    <default-view />
  </v-app>
</template>

<script setup>
  import NavBar from '/src/components/NavBar.vue';
  import HomeCarousel from '/src/components/HomeCarousel.vue';
  //import DefaultBar from './AppBar.vue'
  import DefaultView from './View.vue'
</script>

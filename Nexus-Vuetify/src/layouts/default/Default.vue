<template>
  <v-app class="app">
    <NavBar :isConnected="test"/>
    <div class="contentPage">
      <default-view  class="vue"/>
      <Footer/>
    </div>
  </v-app>
</template>

<script setup>
  import NavBar from '/src/components/NavBar.vue';
  import Footer from '/src/components/Footer.vue';
  import DefaultView from './View.vue'
  var test = 'bla bla';
</script>

<style src="../../styles//settings.scss"></style>

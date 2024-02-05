<template>
  <v-app class="app">
    <NavBar class="leNav"/>
    <div class="contentPage">
      <default-view  class="vue"/>
      <Footer/>
    </div>
  </v-app>
</template>

<script setup>
  import NavBar from '../../components/NavBar.vue';
  import Footer from '../../components/Footer.vue';
  import DefaultView from './View.vue'
</script>

<style src="../../styles//settings.scss"></style>

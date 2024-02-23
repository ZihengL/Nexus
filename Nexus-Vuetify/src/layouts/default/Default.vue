<template>
  <v-app class="app">
    <div class="all">
      <NavBar class="leNav glass"/>
      <div class="contentPage">
        <default-view  class="vue"/>
        <Footer/>
      </div>
    </div>
  </v-app>
</template>

<script setup>
  import NavBar from '../../components/NavBar.vue';
  import Footer from '../../components/Footer.vue';
  import DefaultView from './View.vue'

  
</script>

<style src="../../styles//settings.scss"></style>

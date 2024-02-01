<template>
  <v-app>
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
<style>
.contentPage {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
    width: 100%;
    min-height: 100svh;
    margin-top: 2%;
}
.vue {
  width: 100%;
}
</style>

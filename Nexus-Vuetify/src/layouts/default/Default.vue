<template>
  <v-app>
    <NavBar :isConnected="test"/>
    <div class="content">
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
.content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
    width: 100%;
    min-height: 100svh;
    margin-top: 5%;
}
.vue {
  width: 100%;
}
</style>

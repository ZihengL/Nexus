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
    border: 2px solid red;
    margin-top: 5%;
}
Footer {
  border: 2px solid blue;
}
.vue {
  border: 2px solid rgb(0, 255, 94);
  width: 100%;
}
</style>

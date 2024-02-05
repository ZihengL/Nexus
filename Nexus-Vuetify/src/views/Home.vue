<template>
  <div class="home">
    <Homecarrousel />
    <Info />
  </div>
</template>

<script scoped>
  //import Card from '../components/Card.vue';
  import Homecarrousel from '../components/HomeCarrousel.vue';
  import Info from '../components/Text.vue';
  export default {
    components: {
      //Card,
      Info,
      Homecarrousel
    },
  };
</script>

<style lang="scss" scoped>
</style>




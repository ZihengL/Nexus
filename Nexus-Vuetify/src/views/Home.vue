<template>
  <div class="home">
    <Homecarrousel />
    <Info style="margin: 2% auto;"/>
  </div>
</template>

<script scoped>
  //import Card from '../components/Card.vue';
  import Homecarrousel from '../components/home/Homecarrousel.vue';
  import Info from '../components/home/Text.vue';
  export default {
    components: {
      //Card,
      Info,
      Homecarrousel
    },
  };
</script>

<style lang="scss" scoped>
  .home {
    text-align: center;
  }
</style>




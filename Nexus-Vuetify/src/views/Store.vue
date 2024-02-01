<template>
  <div>
      <Search/>
      <Filteer/>
      <h1>Boutique</h1>
      <Card/>
  </div>
</template>
<script scoped>
  import Card from '../components/Card.vue';
  import Search from '../components/Searchbar.vue';
  import Filteer from '../components/Filter.vue';
  export default {
    components: {
      Card,
      Search,
      Filteer,
    },
  };
</script>
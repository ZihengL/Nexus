<template>
    <Search/>
    <Filter/>
    <h1>Boutique</h1>
    <Card/>
</template>
<script scoped>
import Card from '../components/Card.vue';
import Search from '../components/Searchbar.vue';
import Filter from '../components/Filter.vue';
export default {
  components: {
    Card,
    Search,
    Filter,
  },
};
</script>
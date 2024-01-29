<template> 
  <Info/>
  <h1 class="title-page">Trending game</h1> 
  <Card/>
  
</template>

<script scoped>
import  Card from '../components/Card.vue';
import Info from '../components/Text.vue';
export default {
  components: {
    Card,
    Info,
  },
};
</script>

<style scoped>
.title-page{
  margin-top: 2%;
}

.carousel {
  border-top: 100px;
}

.title-page {
  font-size: 100px;
  color:blueviolet;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>




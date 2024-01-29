<template>  
  <HomeCarousel class="carousel"/>
  <Info/>
  <h1 class="title-page">Trending game</h1> 
  <Card/>
  
</template>

<script scoped>
import HomeCarousel from '../components/Homecarousel.vue';
import  Card from '../components/Card.vue';
import Info from '../components/Text.vue';
export default {
  components: {
    HomeCarousel,
    Card,
    Info,
  },
};
</script>
<style scoped>
.title-page{
  margin-top: 2%;
  text-align: center;
  color: blueviolet;

}
</style>



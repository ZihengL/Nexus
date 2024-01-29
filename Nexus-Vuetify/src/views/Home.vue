<template>  
  <HomeCarousel class="carousel"/>
  <h2 class="title-page">Trending game</h2> 
  <Card/>
  
</template>

<script scoped>
import HomeCarousel from '../components/Homecarousel.vue';
import  Card from '../components/Card.vue';

export default {
  components: {
    HomeCarousel,
    Card,
  },
  data() {
    return {
      // Your data properties
    };
  },
  // ... other options like methods, computed, etc.
};
</script>

<style scoped>
body {
  background-color: blue;
}
.carousel {

}
.title-page {
  font-size: 75px;
  color: blue;
  margin-top: 2%;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>

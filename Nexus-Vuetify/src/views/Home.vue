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
  games: [
      {
          title: 'Tekken 8',
          subtitle: null,
          image: '../src/assets/img/tekken.png',
          price: 'C$67.69',
          discount: '-34%'
        },
        {
          title: 'Ready or Not',
          subtitle: null,
          image: '../src/assets/img/zomboid.png',
          price: 'C$53.99',
          discount: '-26%'
        },
        {
          title: 'Cyberpunk 2077: Ultimate Edition',
          subtitle: null,
          image: '../src/assets/img/palworld.png',
          price: 'C$50.73',
          discount: '-61%'
        },
        {
          title: 'Tekken 8',
          subtitle: null,
          image: '../src/assets/img/elden_ring.png',
          price: 'C$67.69',
          discount: '-34%'
        },
        // ...more games
      ]
    };
</script>

<style scoped>
<<<<<<< HEAD
.title-page{
  margin-top: 2%;
}
body {
  background-color: blue;
}

.carousel {
  border-top: 100px;
}

.title-page {
  font-size: 100px;
  color: blue;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>
<style scoped>
body {
  background-color: blue;
}

.carousel {
  border-top: 100px;
}

.title-page {
  font-size: 100px;
  color: blue;
  text-align: center;
  color: blueviolet;
}
</style>



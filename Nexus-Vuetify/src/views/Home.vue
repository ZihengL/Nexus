<template>
<Homecarousel class="carousel"/>
<h2 class="title-page">Trending game</h2>
<v-container fluid>
      <v-row>
        <v-col
          v-for="(game, index) in games"
          :key="index"
          cols="3"
          sm="6"
          md="4"
          lg="3"
        >
          <v-card>
            <v-img :src="game.image" aspect-ratio="1.7">
              <v-row class="fill-height ma-0" align="end" justify="start">
                <span class="discount" v-if="game.discount">{{ game.discount }}</span>
              </v-row>
            </v-img>
            <v-card-title>{{ game.title }}</v-card-title>
            <v-card-subtitle v-if="game.subtitle">{{ game.subtitle }}</v-card-subtitle>
            <v-card-text>
              <div class="price">{{ game.price }}</div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

</template>

<script>
import Homecarousel from '../components/Homecarousel.vue'

export default {
    data() {
      return {
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
    }
  };
</script>
<style scoped>
body{
  background-color: blue;
}
.carousel{
  border-top:  100px;
}
.title-page{
  font-size: 100px;
  color: blue;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>
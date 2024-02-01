<template><v-container fluid>
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

<script scoped>
export default {
    data() {
      return {
        games: [
          {
            title: 'Tekken 8',
            subtitle: null,
            image: '../src/assets/img/tekken.png',
            price: 'C$67.69',
            Genre: 'Combat'
          },
          {
            title: 'Ready or Not',
            subtitle: null,
            image: '../src/assets/img/zomboid.png',
            price: 'C$53.99',
            Genre: 'FPS'
          },
          {
            title: 'Palworld',
            subtitle: null,
            image: '../src/assets/img/palworld.png',
            price: 'C$50.73',
            Genre: 'Adventure'
          }, 
          {
            title: 'Apex',
            subtitle: null,
            image: '../src/assets/img/apex.png',
            price: 'Gratuit',
            Genre: 'FPS'
          }, 
          {
            title: 'dontstarve',
            subtitle: null,
            image: '../src/assets/img/dontstarve.png',
            price: 'C$67.69',
            Genre: 'Survival'
          },
          {
            title: 'satisfactory',
            subtitle: null,
            image: '../src/assets/img/satisfactory.png',
            price: 'C$80.69',
            Genre: 'Solo'
          },
          {
            title: 'Solo Leveling Arise',
            subtitle: null,
            image: '../src/assets/img/solo_leveling.png',
            price: 'Gratuit',
            Genre: 'Farming',
          },
          // ...more games
        ]
      };
    },
  };
</script>
<style scoped>
</style>
<template>
  <div id="gameVue">
    <div class="content">
      <game class="gameCarrousel" />
      <div class="gameInfo glass">
        <div class="gameImg">
          <img src="../assets/image/img1.png" alt="#">
        </div>
        <div class="descript">
            {{ LeGame.title }}
        </div>
        <div class="descript">
            <p>{{ LeGame.description }}</p>
          <p>Lorem ipsum dolor sit, amet consectetur adipisicing
            elit. Vel debitis illum reprehenderit recusandae
            reiciendis quasi magni eum harum minus ipsam sint optio
            quas expedita doloremque maiores enim minima, voluptas unde.</p>
        </div>
        <div class="ratings">
          <v-rating hover :length="5" :size="32" :model-value="3" active-color="primary" class="rat" />
        </div>
        <div class="devs">
          <p><b>Developeur : </b> 2Braise</p>
        </div>
        <div class="tags">
          <a href="#" class="glow">Fps</a>
          <a href="#" class="glow">Aventure</a>
          <a href="#" class="glow">Drame</a>
          <a href="#" class="glow">Algerient</a>
        </div>
        <div class="fieldBtn">
          <div class="btn-layer"></div>
          <v-btn density="default" class="submit glow" @click="toggleProfile">
            Telecharger
          </v-btn>
        </div>
      </div>
    </div>
    <div class="Avis">
      <AvisRecent />
      <AvisRating />

    </div>

  </div>
</template>

<script setup>
    import game from '../components/GameCarrousel.vue'
    import { fetchData } from '../JS/fetch'
    import { ref, onMounted,  defineProps} from 'vue';

    const props = defineProps(['idGame']);
    let LeGame;

    onMounted(() => {
        console.log('Game ID : ', props.idGame);
        fetchData("games", "getOne", "id", props.idGame, null, "GET")
        .then(data => {
            LeGame = data;
            console.log('LeGame : ', LeGame);
        })
        .catch(error => {
        // Handle errors if any
        console.error('Error fetching data:', error);
        });
    });
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>


<template>
  <div id="gameVue">
    <div v-if="LeGame && devName" class="content">
      <game class="gameCarrousel" />
      <div class="gameInfo roundBorderSmall glass">
        <div class="gameImg">
          <img src="../assets/image/img1.png" alt="#" class="roundBorderSmall">
        </div>
        <div class="descript">
          <p>{{ LeGame.title }}</p>
        </div>
        <div class="descript">
          <p>{{ LeGame.description }}</p>
        </div>
        <div class="ratings">
          <v-rating hover half-increments :length="5" :size="32" :model-value="LeGame.ratingAverage"
            active-color="primary" class="rat" />
        </div>
        <div class="devs">
          <p><b>Developeur : </b> {{ devName }}</p>
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
      <div class="Pagin">
        <AvisRecent class="recent" />
        <Pagination />
      </div>
      <AvisRating class="rate" />

    </div>

  </div>
</template>

<script setup>
import { defineProps, onMounted, ref } from 'vue';
import { fetchData } from '../JS/fetch';
import AvisRating from '../components/AvisRating.vue';
import AvisRecent from '../components/AvisRecent.vue';
import game from '../components/GameCarrousel.vue';
import Pagination from '../components/Pagination.vue';

const props = defineProps(['idGame']);
const LeGame = ref(null);
const devName = ref(null);

onMounted(async () => {
  try {
    const dataGame = await fetchData("games", "getOne", "id", props.idGame, null, "GET");
    LeGame.value = dataGame;
    //console.log('LeGame : ' , LeGame._rawValue.developerID)

    if (LeGame.value) {
      const devId = LeGame._rawValue.developerID
      console.log('devId : ', devId)

      const filters = {
        id: devId,
      }
      const sorting = {
        id: false
      }

      const includedColumns = ['id', 'user']
      const jsonBody = { filters, sorting, includedColumns }

      const dataDevs = await fetchData('users', 'getAllMatching', null, null, jsonBody, 'POST');
      let devName2 = dataDevs;
      devName.value = devName2[0].user
      console.log('devs : ', devName._value)
    }

  } catch (error) {
    console.error('Error fetching data:', error);
  }
});
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>


<template>
  <div id="gameVue">
    <div v-if="LeGame" class="content">
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
          <!--<p>Lorem ipsum dolor sit, amet consectetur adipisicing
            elit. Vel debitis illum reprehenderit recusandae
            reiciendis quasi magni eum harum minus ipsam sint optio
            quas expedita doloremque maiores enim minima, voluptas unde.</p>-->
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
      <AvisRecent class="recent"/>
      <AvisRating class="rate"/>

    </div>

  </div>
</template>

<script setup>
  import game from '../components/GameCarrousel.vue'
  import { fetchData } from '../JS/fetch';
  import { defineProps, ref, onMounted } from 'vue';
  import AvisRecent from '../components/AvisRecent.vue'
  import AvisRating from '../components/AvisRating.vue'

  const props = defineProps(['idGame']);
  const LeGame = ref(null);
  const devName = ref(null);

  onMounted(async () => {
    try {
      const dataGame = await fetchData("games", "getOne", "id",  props.idGame, null, "GET");
      LeGame.value = dataGame;

      const dataDevs = await fetchData("user", "getOne", "id",  LeGame.developerId, null, "GET");
      devName.value = dataDevs;
      console.log('devs : ' , devName)
    } catch (error) { 
      console.error('Error fetching data:', error);
    }
  });
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>


<template>
  <div id="gameVue">
    <div v-if="gameInfos.leGame && gameInfos.devName" class="content">
      <game class="gameCarrousel" />
      <div class="gameInfo roundBorderSmall glass">
        <div class="gameImg">
          <img
            src="../assets/image/img1.png"
            alt="#"
            class="roundBorderSmall"
          />
        </div>
        <div class="descript">
          <p>{{ gameInfos.leGame.title }}</p>
        </div>
        <div class="descript">
          <p>{{ gameInfos.leGame.description }}</p>
        </div>
        <div class="ratings">
          <v-rating
            readonly
            half-increments
            :length="5"
            :size="32"
            :model-value="gameInfos.leGame.ratingAverage"
            active-color="primary"
            class="rat"
          />
        </div>
        <div class="devs">
          <p><b>Developeur : </b> {{ gameInfos.devName }}</p>
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
import { defineProps, onMounted, reactive } from "vue";
import { fetchData } from "../JS/fetch";
import AvisRating from "../components/AvisRating.vue";
import AvisRecent from "../components/AvisRecent.vue";
import game from "../components/GameCarrousel.vue";
import Pagination from "../components/Pagination.vue";

const gameInfos = reactive({
  leGame: [],
  devName: {
    type: String,
    default: "error",
  },
});

const props = defineProps({
  idGame: {
    type: Number,
    default: 4,
  },
});
// const LeGame = ref(null);
// const devName = ref(null);

const getLeGame = async function () {
  try {
    const dataGame = await fetchData(
      "games",
      "getOne",
      "id",
      props.idGame,
      null,
      null,
      null,
      "GET"
    );
    gameInfos.leGame = dataGame;
    console.log("LeGame: ", gameInfos.leGame);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const getDevName = async function () {
  try {
    if (gameInfos.leGame.length > 0) {
      const dataUsername = await fetchData(
        "users",
        "getOne",
        "id",
        gameInfos.leGame.developerID,
        null,
        null,
        null,
        "GET"
      );
      gameInfos.devName = dataUsername.username;
    }
    console.log(" gameInfos.devName : ", gameInfos.devName);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

onMounted(async () => {
  try {
    await getLeGame();
    await getDevName();
  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>

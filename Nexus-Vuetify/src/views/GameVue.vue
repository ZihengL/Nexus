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
          <a href="#" class="glow" v-for="tag in gameInfos.tags" :key="tag.id">{{ tag.name }}</a>
        </div>

        <div class="fieldBtn">
          <div class="btn-layer"></div>
          <v-btn
            :id="gameInfos.leGame.id"
            density="default"
            class="submit glow"
            @click="toggleProfile"
          >
            Telecharger
          </v-btn>
        </div>
      </div>
    </div>

    <div class="Avis">
      <div class="Pagin">
        <!-- <AvisRecent class="recent" /> -->
        <ReviewsListComponent :title="gameInfos.reviewDate_titre"  :gameID=Number(idGame)  :sorting="gameInfos.sortByDate"></ReviewsListComponent>
      </div>
      <!-- <AvisRating class="rate" /> -->
      <ReviewsListComponent :title="gameInfos.reviewRating_titre" :gameID=Number(idGame) :sorting="gameInfos.sortByRating"></ReviewsListComponent>
      <!-- <PaginationComponent></PaginationComponent> -->
    
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, reactive } from "vue";
// import AvisRating from "../components/AvisRating.vue";
// import AvisRecent from "../components/AvisRecent.vue";
import game from "../components/game/GameCarrousel.vue";
// import PaginationComponent from "../components/PaginationComponent.vue";
import ReviewsListComponent from "../components/reviewsListComponent.vue";
import {getGameDetailsWithDeveloperName, getGameReviewsUsernames } from '../JS/fetchServices';

const gameInfos = reactive({
  leGame: {}, 
  reviewDate_titre : "Avis les plus récents",
  reviewRating_titre : "Avis par rating",
  devName: "error", 
  tags: ["NO TAGS"], 
  sortByDate: {timestamp: false},
  sortByRating: {rating: false},
});

const props = defineProps({
  idGame: {
    type : Number,
    default: 4,
  },
});

onMounted(async () => {
  // console.log("sortByRating : ", gameInfos.sortByRating)
  try {
    let dataGame = await getGameDetailsWithDeveloperName(props.idGame)
    gameInfos.leGame = dataGame
    gameInfos.tags = gameInfos.leGame.tags
    gameInfos.devName = gameInfos.leGame.devName
    console.log("leGame : ", gameInfos.leGame)

    // let test = getGameReviewsUsernames(props.idGame, gameInfos.sortByDate)
    // console.log("test : ", test)

  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>

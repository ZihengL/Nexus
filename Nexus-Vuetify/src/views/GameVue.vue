<template>
  <div  v-if="gameInfos.leGame && gameInfos.devName" id="gameVue">
    <div class="content">
      <game class="gameCarrousel" :idJeux="props.idGame"/>
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

        <btnComp :contenu="'Telecharger'" @toggle-btn="toggleProfile"/>
      </div>
    </div>

    <div v-if="reviewTemp > 0" class="Avis ">
      <div class="Pagin wrapper roundBorderSmall glass">
        <div class="form-container">
          <div class="slide-controls roundBorderSmall">
            <input
              type="radio"
              name="slide"
              id="login"
              v-model="isLogin"
              value="true"
              checked
            />
            <input
              type="radio"
              name="slide"
              id="signup"
              v-model="isLogin"
              value="false"
            />
            <label for="login" class="slide login" @click="toggleLogin()"
              >Nouveau</label
            >
            <label for="signup" class="slide signup" @click="toggleSignup()"
              >Anciens</label
            >
            <div class="slider-tab"></div>
          </div>
          <div class="form-inner">
            <Avis class="recent" :idGame="gameInfos.leGame.id" :sort="1"/>
            <Avis class="old "  :idGame="gameInfos.leGame.id" :sort="2"/>
          </div>
        </div>

        <myAvis/>
      </div>
      <Avis class="rate glass"  :idGame="gameInfos.leGame.id" :sort="0"/>
    </div>

    <div v-else class="avisVide  roundBorderSmall glass">
      <p>Aucun commentaire pour l'instant </p>
      <myAvis/>
    </div>
  </div>
</template>

<script setup>
import btnComp from "../components/btnComponent.vue";
import myAvis from "../components/game/myAvis.vue";
import { defineProps, onMounted, reactive, ref } from "vue";
import AvisRating from "../components/game/AvisRating.vue";
import Avis from "../components/game/Avis.vue";
import game from "../components/game/GameCarrousel.vue";
// import PaginationComponent from "../components/PaginationComponent.vue";
import ReviewsListComponent from "../components/reviewsListComponent.vue";
import {getGameDetailsWithDeveloperName, getReviews } from '../JS/fetchServices';

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
let reviewTemp = ref(null);

const isLogin = ref(true);
const toggleLogin = () => {
  isLogin.value = true;
  const gamessShow = document.querySelector(".recent"); // Use class selector
 //const gamesContainer = document.querySelector(".gamesss"); // Use class selector
  if (gamessShow) {
    gamessShow.style.marginLeft = "0%";
    gamesContainer.style.marginLeft = "0%";
  }
};

const toggleSignup = () => {
  isLogin.value = false;
  const gamessShow = document.querySelector(".recent"); // Use class selector
  //const gamesContainer = document.querySelector(".gamesss"); // Use class selector
  if (gamessShow) {
    gamessShow.style.marginLeft = "-50%";
    gamesContainer.style.marginLeft = "-50%";
  }
};

onMounted(async () => {
  // console.log("sortByRating : ", gameInfos.sortByRating)
  try {
    let dataGame = await getGameDetailsWithDeveloperName(props.idGame)
    gameInfos.leGame = dataGame
    gameInfos.tags = gameInfos.leGame.tags
    gameInfos.devName = gameInfos.leGame.devName
    //console.log("leGame : ", gameInfos.leGame)
    let dataReview = await getReviews(props.idGame)
    reviewTemp.value = dataReview.length
    //console.log('rev lenght : ', reviewTemp.value)

  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
</script>


<style src="../styles/SignRegisterStyle.scss" lang="scss"></style>
<style src="../styles/GameVueStyle.scss" lang="scss"></style>

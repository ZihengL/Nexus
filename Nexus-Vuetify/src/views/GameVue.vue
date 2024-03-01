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
            @click="uploadZipFile"
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
import { getStorage, ref as storageRef, uploadBytes } from "firebase/storage";
import game from "../components/game/GameCarrousel.vue";
// import PaginationComponent from "../components/PaginationComponent.vue";
import ReviewsListComponent from "../components/reviewsListComponent.vue";
import {getGameDetailsWithDeveloperName } from '../JS/fetchServices';

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

  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
// Assume Firebase is initialized elsewhere, directly reference the storage
const storage = getStorage();

// Method to upload a file
const uploadZipFile = async () => {
  // Example file to upload, you might want to replace this with actual file selection logic
  const file = new Blob(["This is a test ZIP file content"], { type: 'application/zip' });
  const fileName = `snake.zip`; // Example file name, replace as needed
  const fileRef = storageRef(storage, `Games/${fileName}`);
  
  try {
    await uploadBytes(fileRef, file);
    console.log(`${fileName} uploaded successfully`);
  } catch (error) {
    console.error("Failed to upload file:", error);
  }
};
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>

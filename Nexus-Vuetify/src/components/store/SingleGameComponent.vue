<template>
  <router-link
    v-if="singleGame_data.leGame"
    :to="{ name: 'Game', params: { idGame: props.idGame, urlImg:  singleGame_data.image.image} }"
    class="single glass2 roundBorderSmall"
  >
    <div class="img roundBorderSmall">
      <img
        :src="singleGame_data.image.image"
        alt="image par défaut"
        class="roundBorderSmall gameImg"
      />
      <p class="roundBorderSmall">
        {{ singleGame_data.leGame.description }}
      </p>
    </div>
    <h3>{{ singleGame_data.leGame.title }}</h3>
    <h4>{{ singleGame_data.leGame.devName }}</h4>
    <div class="ratings">
      <v-rating
        hover
        readonly
        half-increments
        :length="5"
        :size="32"
        :model-value="singleGame_data.leGame.ratingAverage"
        active-color="rgba(3,33,76,1)"
        class="rat"
      />
    </div>
    <div class="tags">
      <ul class="glow" v-for="tag in limitedTags" :key="tag.id">{{ tag.name }}</ul>
    </div>
  </router-link>
</template>

<script setup>
// import { fetchData } from "../../JS/fetch";
import { reactive, onMounted, computed } from "vue";
import { getStorage, ref, getDownloadURL } from "firebase/storage";
import { getGameDetailsWithDeveloperName } from "../../JS/fetchServices";
import defaultImage from '@/assets/imgJeuxLogo/noImg.jpg';

const storage = getStorage();

const props = defineProps({
  idGame: Number,
});

const singleGame_data = reactive({
  leGame: {},
  tags :[],
  DEFAULT_IMAGE_PATH : defaultImage,
  image: String,
});

const limitedTags = computed(() => {
  // console.log('tags : ', singleGame_data.tags);
  if (singleGame_data.tags.length > 3){
    return singleGame_data.tags.slice(0, 3);
  }
  else {
    return singleGame_data.tags;
  }
 
});

async function fetchGameImages(gameId) {
  try {
    const imagePath = `Games/${gameId}/media/${gameId}_Store.png`;
    //console.log('imagePath : ', imagePath);
    const imageRef = ref(storage, imagePath);

    try {
      const url = await getDownloadURL(imageRef);
      return { id: gameId, image: url }; // Corrected the return statement
    } catch (error) {
      console.error(`Error fetching image for ${gameId}:`, error);
      return { id: gameId, image: singleGame_data.DEFAULT_IMAGE_PATH };// Fallback image
    }
  } catch (error) {
    console.error("Error fetching game images:", error);
    throw error; // Re-throw the error to handle it at a higher level if needed
  }
}


async function getGameInfos() {
  try {
    // console.log("props.idGame : ", props.idGame);
    // console.log("singleGame props.idGame :", props.idGame);
    singleGame_data.leGame = await getGameDetailsWithDeveloperName(
      props.idGame
    );
    // console.log(
    //   "typeOf singleGame_data.leGame:",
    //   typeof singleGame_data.leGame
    // );
    if (singleGame_data.leGame) {
      const image = await fetchGameImages(singleGame_data.leGame.id)
      singleGame_data.image = image
      // singleGame_data.tags = singleGame_data.leGame.tags
      // console.log("singleGame_data.tags : ", singleGame_data.tags);
    }
    //console.log('url : ', singleGame_data.image.image)
    // singleGame_data.leGame = singleGame_data.leGame.image;
    // console.log("singleGame data :", data);
    // singleGame_data.leGame = await data;
    // console.log("singleGame leGame :", singleGame_data.leGame);
  } catch (error) {
    console.error("Error fetching game details:", error);
  }
}
onMounted(async () => {
  await getGameInfos();
});
</script>

<style lang="scss">
.single:hover {
  overflow-x: hidden;
  overflow-y: hidden;
}
.single  {
  text-decoration: none;
  color: var(--light-trans-2);
  padding-bottom: 5%;
  display: flex;
  flex-direction: column;
  height: 20rem;

  // padding: 5%;
  .img {
    flex: 6;
    position: relative;
    width: 100%;
    height: 100%;

    img {
      width: 100%;
      height: 100%;
      display: inline-block;
      transition: opacity 0.3s ease;
      opacity: 1;
      object-fit: fill; 
    }

    p {
      display: none;
      position: absolute;
      text-align: center;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 2;
    }
  }
  h3, h4, .ratings, .tags {
    flex: 1;
  }
  ul {
    
    display: flex;
    justify-content: space-around;
    flex-direction: row;
    list-style: none;
  }
}
.glass2:hover {
  .img img {
    opacity: 0.3;
  }

  .img p {
    display: inline-block;
    width: 90%;
  }
}
</style>

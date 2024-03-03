<template>
  <router-link
    v-if="singleGame_data.leGame"
    :to="{ name: 'Game', params: { idGame: props.idGame } }"
    class="glass2 roundBorderSmall"
  >
    <div class="img roundBorderSmall">
      <img
        :src="singleGame_data.image"
        alt="nothingBro"
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
          <ul class="glow" v-for="tag in singleGame_data.tags" :key="tag.id">{{ tag.name }}</ul>
    </div>
  </router-link>
</template>

<script setup>
// import { fetchData } from "../../JS/fetch";
import { reactive, onMounted } from "vue";

const props = defineProps({
  idGame: Number,
});

const singleGame_data = reactive({
  leGame: {},
  tags :[],
  image: String,
});


import { getGameDetailsWithDeveloperName } from "../../JS/fetchServices";

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
      singleGame_data.image = singleGame_data.leGame.image
        ? singleGame_data.leGame.image
        : "/src/assets/img/dontstarve.png";
        singleGame_data.tags =singleGame_data.leGame.tags
    }
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
.glass2 {
  text-decoration: none;
  color: var(--light-trans-2);
  padding-bottom: 2%;
  .img {
    flex: 5;
    position: relative;

    img {
      width: 100%;
      display: inline-block;
      transition: opacity 0.3s ease;
      opacity: 1;
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

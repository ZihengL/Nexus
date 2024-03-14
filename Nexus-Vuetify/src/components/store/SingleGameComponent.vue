<template>
  <router-link
    v-if="singleGame_data"
    :to="{ name: 'Game', params: { idGame: singleGame_data.id} }"
    class="single glass2 roundBorderSmall"
  >
    <div class="img roundBorderSmall">
      <img
        :src="singleGame_data.image"
        alt="nothingBro"
        class="roundBorderSmall gameImg"
      />
      <p class="roundBorderSmall">
        {{ singleGame_data.description }}
      </p>
    </div>
    <h3>{{ singleGame_data.title }}</h3>
    <h4>{{ singleGame_data.devName }}</h4>
    <div class="ratings">
      <v-rating
        hover
        readonly
        half-increments
        :length="5"
        :size="32"
        :model-value="singleGame_data.ratingAverage"
        active-color="rgba(3,33,76,1)"
        class="rat"
      />
    </div>
    <div class="tags" v-if="singleGame_data.tags">
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

const props = defineProps(['gameList']);

let singleGame_data = props.gameList;
console.log('one object', singleGame_data);

const limitedTags = computed(() => {
  
  if (singleGame_data.tags.length > 3){
    let data = singleGame_data.tags.slice(0, 4);
    //console.log('data nnn : ', data);
    return data;
  }
  else {
    let data = singleGame_data.tags;
    //console.log('data nnn : ', data);
    return data
  }
 
});
</script>

<style lang="scss">
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
  .tags {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
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

<template>
  <div v-if="props.gameList.length > 0" class="glass">
    <div id="storeComp">
      <SingleGameComponent
        v-for="game in arrayStore"
        :key="game.id"
        :gameList=" game"
        class="vuee"
      />
    </div>
    <Pagination v-if="nbPage > 1" :nbPageProps="nbPage" class="pag" @nbPage="getNbPage()" :type="'Store'"/>
  </div>
  <div v-else>
    {{ gameList_data.errorMsg }}
  </div>
</template>

<script setup>
import SingleGameComponent from "./SingleGameComponent.vue";
import  Pagination  from "../PaginationComponent.vue";
import { reactive, ref, onMounted, watch } from "vue";
import PaginationManager from "@/JS/pagination";

const nbMax = 6;
let nbPage = null;
let paginationNb = 1;

 const getNbPage = () => {
  paginationNb = PaginationManager.getStorePage()
  let max = paginationNb * nbMax;
  let min = max - nbMax;
  if (props.gameList.length < max){
    max = props.gameList.length
  }
  // console.log('min  w2 : ', min);
  // console.log('max  w2 : ', max);
  // console.log('all lent w2  : ', props.gameList.length);
  //console.log("props.gameList : ", props.gameList)
  arrayStore.value = props.gameList.slice(min, max);

  // console.log('array  w2 : ', arrayStore.value.length);
  // console.log('paginationNb emit : ', paginationNb)
 }

let arrayStore = ref([]);

const props = defineProps({
  gameList: {
    type: Array,
    default: () => ([]),
  },
});


const gameList_data = reactive({
  listeJeux: [],
  errorMsg: "Erreur Aucuns Jeux Dans La Liste",
});

watch(
  () => props.gameList,
  (newVal, oldVal) => {
    // nbMax = nb jeux par page
    // paginationNb = la page de pagination ou on est
    
    let max = paginationNb * nbMax;
    let min = max - nbMax;
    if (props.gameList.length < max){
      max = props.gameList.length
    }

    arrayStore.value = props.gameList.slice(min, max);
    console.log("props.gameList : ", props.gameList)
    
    if (props.gameList.length > nbMax) {
      nbPage = props.gameList.length / nbMax;
    } else {
      nbPage = 1; // ou une autre valeur si vous préférez
    }
  },
  { deep: true }
);
</script>

<style lang="scss">
#storeComp {
  padding: 0%;
  width: 100%;
  display: grid;
  grid-template-columns: auto auto auto;
  gap: 2%;
  padding: 2% 2% 5% 2%;
}
</style>

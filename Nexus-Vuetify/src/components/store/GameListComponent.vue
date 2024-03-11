<template>
  <div v-if="props.gameList.length > 0" class="glass">
    <div id="storeComp">
      <SingleGameComponent
        v-for="game in arrayStore"
        :key="game.id"
        :idGame="game.id"
        class="vuee"
      />
    </div>
    <Pagination :nbPageProps="nbPage" class="pag" @nbPage="getNbPage()"/>
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

let nbPage = null;
let paginationNb = 1;
 const getNbPage = () => {
  paginationNb = PaginationManager.getPage()
  console.log('paginationNb emit : ', paginationNb)
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
  errorMsg: "Error no game in List",
});

watch(
  () => props.gameList,
  (newVal, oldVal) => {
    let max = paginationNb * 9;
    let min = max - 9;
    if (props.gameList.length < max){
      max = props.gameList.length
    }

    arrayStore.value = props.gameList.slice(min, max);

    console.log('array : ', arrayStore.value.length);

    if (props.gameList.length > 9) {
      nbPage = props.gameList.length / 9;
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

<template>
  <div v-if="props.gameList.length > 0" class="glass">
    <div id="storeComp">
      <SingleGameComponent
        v-for="game in gameList"
        :key="game.id"
        :gameList=" game"
        class="vuee"
      />
    </div>
    <Pagination v-if="nbPage > 1" :nbPageProps="props.nbPage" class="pag" @nbPage="getNbPage()" :type="'Store'"/>
  </div>
  <div v-else>
    {{ gameList_data.errorMsg }}
  </div>
</template>

<script setup>
import SingleGameComponent from "./SingleGameComponent.vue";
import  Pagination  from "../PaginationComponent.vue";
import { reactive, ref, onMounted, watch } from "vue";

const nbMax = 3;
//let paginationNb = 1;

const props = defineProps({
  gameList: {
    type: Array,
    default: () => ([]),
  },
  nbPage: {
    type: Number,
    default: 1,
  }
});

const emit = defineEmits(['pageNb']);
 const getNbPage = () => {
  emit('pageNb');
  // paginationNb = PaginationManager.getStorePage()
  // let max = paginationNb * nbMax;
  // let min = max - nbMax;
  // if (props.gameList.length < max){
  //   max = props.gameList.length
  // }
  // arrayStore.value = props.gameList.slice(min, max);
 }

// let arrayStore = ref([]);




const gameList_data = reactive({
  listeJeux: [],
  errorMsg: "Erreur Aucuns Jeux Dans La Liste",
});

watch(
  () => props.gameList,
  (newVal, oldVal) => {
    console.log('game liste get w : ', newVal)
    //window.location.reload();
  },
  { deep: true }
);

onMounted(async () => {
  
  console.log('game liste get m : ', props.gameList)
});
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

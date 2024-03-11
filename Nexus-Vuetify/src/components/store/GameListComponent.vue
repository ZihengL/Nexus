<template>
  <div v-if="props.gameList.length > 0 && nbPage != null" class="glass">
    <div id="storeComp">
      <SingleGameComponent
        v-for="game in props.gameList"
        :key="game.id"
        :idGame="game.id"
        class="vuee"
      />
    </div>
    <Pagination :nbPageProps="nbPage" class="pag"/>
  </div>
  <div v-else>
    {{ gameList_data.errorMsg }}
  </div>
</template>

<script setup>
import SingleGameComponent from "./SingleGameComponent.vue";
import  Pagination  from "../PaginationComponent.vue";
// import { fetchData } from "../../JS/fetch";
// let listeJeux = ref(null);
import { reactive, onMounted, watch } from "vue";
let nbPage = null;
const props = defineProps({
  gameList: {
    type: Array,
    default: () => {},
  },
});

const gameList_data = reactive({
  listeJeux: [],
  errorMsg: "Error no game in List",
});


watch(
  () => gameList_data.gameList_result,
  (newVal, oldVal) => {
    //console.log("gameList_result updated", newVal);
  },
  { deep: true }
);



console.log('nb game div : ', nbPage)
onMounted(async () => {
  if(props.gameList){
    console.log('nb game liste: ', props.gameList)
    if(props.gameList.length > 9){
      nbPage = props.gameList.length / 9;
    }
    else {
      nbPage = 1;
    }
  }

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

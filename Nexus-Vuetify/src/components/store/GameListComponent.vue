<template>
  <div v-if="props.gameList.length" id="storeComp" class="glass">
    <SingleGameComponent
      v-for="game in props.gameList"
      :key="game.id"
      :idGame="game.id"
      class="vuee"
    >
    </SingleGameComponent>
  </div>
  <div v-else>
    {{ gameList_data.errorMsg }}
  </div>
</template>

<script setup>
import SingleGameComponent from "./SingleGameComponent.vue";
// import { fetchData } from "../../JS/fetch";
// let listeJeux = ref(null);
import { reactive, onMounted, watch } from "vue";

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
  () => gameList_data.gameList_result,
  (newVal, oldVal) => {
    //console.log("gameList_result updated", newVal);
  },
  { deep: true }
);


watch(
  () => props.gameList,
  (newVal, oldVal) => {
    console.log("watch props.gameList : ", props.gameList)
  },
  { deep: true }
);

onMounted(async () => {
  // await getGameList();
  console.log("hello")
  if(props.gameList.length > 0){
    console.log("onMounted props.gameList : ", props.gameList)
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

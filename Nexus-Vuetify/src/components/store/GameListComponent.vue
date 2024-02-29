<template>
  <div v-if="gameList_data.listeJeux.length" id="storeComp" class="glass">
    <SingleGameComponent
      v-for="game in gameList_data.listeJeux"
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
import { getAll } from "../../JS/fetchServices";
// import { fetchData } from "../../JS/fetch";
// let listeJeux = ref(null);
import { reactive, onMounted } from "vue";

const gameList_data = reactive({
  listeJeux: [],
  errorMsg: "Error no game in List",
});

async function getGameList() {
  let data = await getAll("games");
  // console.log("Expected an array but got:", typeof data);
  // console.log("gamelist data : ", data);
  if (data) {
    gameList_data.listeJeux = data;
    // console.log("gamelist listeJeux : ", gameList_data.listeJeux);
    // for (let game of gameList_data.listeJeux) {
    //   console.log("game in listeJeux : ", game);
    // }
  }
}


onMounted(async () => {
  await getGameList();
});
</script>

<style lang="scss">
#storeComp {
  padding: 0%;
  width: 100%;
  display: grid;
  grid-template-columns: auto auto;
  gap: 2%;
  padding: 2% 2% 5% 2%;
}
</style>

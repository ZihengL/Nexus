<template>
  <div v-if="gameList_data.listeJeux " id="storeComp" class="glass">
    <SingleGameComponent
      v-for="game in gameList_data.listeJeux "
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
import SingleGameComponent from "../components/store/SingleGameComponent.vue";
import { getAllGamesWithDeveloperName } from "../JS/fetchServices.js";
// let listeJeux = ref(null);
import { reactive, onMounted, watch } from "vue";

const props = defineProps({
  gameList: {
    type: Array,
    default: () => {
      [];
    },
  },
});

const gameList_data = reactive({
  listeJeux: props.gameList,
  errorMsg: "Error no game in List",
});

watch(
  () => gameList_data.gameList_result,
  (newVal, oldVal) => {
    console.log("gameList_result updated", newVal);
  },
  { deep: true }
);

onMounted(async () => {
  if (!props.gameList) {
    gameList_data.listeJeux = await getAllGamesWithDeveloperName();
  }
});
</script>

<style lang="scss">
#storeComp {
  padding: 0%;
  width: 50%;
  display: grid;
  grid-template-columns: auto auto;
  gap: 2%;
//   padding: 2% 2% 5% 2%;
  margin: 20%;
}
</style>

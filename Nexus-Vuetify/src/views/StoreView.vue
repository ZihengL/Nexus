<template>
  <div id="store">
    <div class="search">
      <div class="search">
        <Search
          v-model="store_data.searchQuery"
          :placeholder="store_data.placeholder_title"
          :onSearch="handleSearch"
          class="barreR"
        />
      </div>
    </div>
    <div class="contenue">
      <ListeJeux
        :gameList="store_data.gameList_result"
        :nbPage="nbPage"
        @pageNb="getNbPage"
        class="listeJeux roundBorderSmall"
      />
      <div class="filtre glass roundBorderSmall">
        <FilterComponent
          @update:filters="handleFilterUpdate"
          :filter_title="store_data.filter_title"
        />
      </div>
    </div>
    <div class="pagin">
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, watch } from "vue";
import FilterComponent from "../components/store/FilterComponent.vue";
import Search from "../components/game/Search.vue";
import {
  searchOn_titleOrUsername,
  search_AndFilter,
} from "../JS/store_search.js";
import {  getAllGamesWithDeveloperNameNEW, countAll, getPaging } from "../JS/fetchServices";
import ListeJeux from "../components/store/GameListComponent.vue";
import PaginationManager from "../JS/pagination.js";

const store_data = reactive({
  genres: [
    { name: "action", value: "action", label: "Action" },
    { name: "adventure", value: "adventure", label: "Adventure" },
    { name: "rpg", value: "rpg", label: "RPG" },
    { name: "simulation", value: "simulation", label: "Simulation" },
    { name: "strategy", value: "strategy", label: "Strategy" },
    { name: "sports", value: "sports", label: "Sports" },
  ],
  filter_title: "Filtrer les Jeux",
  placeholder_title: "Trouver un jeu...",
  searchQuery: "",
  gameList_result: [],
});

const nbMax = 3;
let nbPage = null;
let paginationNb = 1;
let count = 1;
let sorting = null;

async function handleFilterUpdate(filterData) {
  console.log("Received filter data:", filterData);
  let titleOrDevName = store_data.searchQuery ?? null;
  let tags = filterData.tags ?? [];
  let sorting = filterData.sorting ?? null;

  let filteredGames = await search_AndFilter(titleOrDevName, tags, sorting);
  //console.log("filteredGames : ", filteredGames);
  store_data.gameList_result = filteredGames
  console.log(" store_data.gameList_result: ", store_data.gameList_result);
}

const handleSearch = async (query) => {
  store_data.searchQuery = query;
  //console.log("searchQuery : ", store_data.searchQuery);
  store_data.gameList_result = await searchOn_titleOrUsername(
    store_data.searchQuery
  );
  //console.log(" store_data.gameList_result: ", store_data.gameList_result);
};

const getNbPage = () => {
  //emit('pageNb');
  paginationNb = PaginationManager.getStorePage();
  
  console.log('newww ', paginationNb);
  let max = paginationNb * nbMax;
  if (count < nbMax) {
    max = count;
  }
  let min = max - nbMax;
  let paging = {limit: nbMax, offset: min};
  
  console.log('min ', min);
  console.log('max ', max);
  store_data.gameList_result = getAllGamesWithDeveloperNameNEW(null, null, null, sorting, paging);
    
    console.log('liste jeux new ', store_data.gameList_result);
 }

// watch(
//   () => paginationNb,
//   (newVal, oldVal) => {
//     let max = newVal * nbMax;
//     let paging = {limit: nbMax, offset: max};
//     store_data.gameList_result = getAllGamesWithDeveloperNameNEW(null, null, null, sorting, paging);
    
//     console.log('liste jeux new ', store_data.gameList_result);
//   },
//   { deep: true }
// );

onMounted(async () => {

  if(store_data.gameList_result.length === 0){
    sorting ={
      id:true
    }
    count = await countAll('games');
    console.log('nb ', count[0].count);
    let max;
    let min;

    if (count < nbMax) {
      nbPage = 2; // ou une autre valeur si vous préférez
      //max = count
      min = 0;
    } else {
      nbPage = count[0].count / nbMax;
      max = paginationNb * nbMax;
      min = max - nbMax;
    }
    console.log('nbPage : ', nbPage)
    let paging = getPaging(nbMax, paginationNb);
    store_data.gameList_result = await getAllGamesWithDeveloperNameNEW(null, null, null, sorting, paging);
    console.log('liste jeux old', store_data.gameList_result);
  }
});
</script>

<style lang="scss">
#store {
  display: flex;
  flex-direction: column;
  width: 70%;
  margin: 1% auto 5% auto;

  .search {
    flex: 1;
    text-align: right;
    justify-content: right;
    margin-bottom: 1%;

    .barreR {
      width: 100%;
    }
  }

  .contenue {
    flex: 5;
    display: block;
    width: 100%;
    padding: 0%;
    //lex-direction: row;

    .listeJeux {
      //flex: 4;
      //display: inline-block;
      float: left;
      width: 65%;
      margin-right: 2%;
    }

    .filtre {
      //flex: 2;
      display: inline-block;
      width: 33%;
    }
  }

  .pagin {
    align-self: flex-start;
  }
}
</style>

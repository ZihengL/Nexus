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
      <!-- Pagination could be added here -->
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted } from "vue";
import FilterComponent from "../components/store/FilterComponent.vue";
// Ensure Pagination is imported when you're ready to use it
// import Pagination from '../components/Pagination.vue';
// import SearchComponent from "../components/game/SearchComponent.vue";
import Search from "../components/game/Search.vue";
import {
  searchOn_titleOrUsername,
  search_AndFilter,
} from "../JS/store_search.js";
import {  getAllGamesWithDeveloperName } from "../JS/fetchServices";
import ListeJeux from "../components/store/GameListComponent.vue";

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


async function handleFilterUpdate(filterData) {
  console.log("Received filter data:", filterData);
  let titleOrDevName = store_data.searchQuery ?? null;
  let tags = filterData.tags ?? [];
  let sorting = filterData.sorting ?? null;

  let filteredGames = await search_AndFilter(titleOrDevName, tags, sorting);
  console.log("filteredGames : ", filteredGames);
  store_data.gameList_result = filteredGames
  console.log(" store_data.gameList_result: ", store_data.gameList_result);
}

const handleSearch = async (query) => {
  store_data.searchQuery = query;
  console.log("searchQuery : ", store_data.searchQuery);
  store_data.gameList_result = await searchOn_titleOrUsername(
    store_data.searchQuery
  );
  console.log(" store_data.gameList_result: ", store_data.gameList_result);
};

onMounted(async () => {
  store_data.gameList_result = await getAllGamesWithDeveloperName();
  console.log(
    " store_data.gameList_result:  onMounted ",
    store_data.gameList_result
  );
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

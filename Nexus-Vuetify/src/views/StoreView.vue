<template>
  <div id="store">
    <div class="search">
      <!-- <SearchComponent
        :class="'barreR'"
        :placeholder="store_data.placeholder_title"
        v-model="store_data.searchQuery"
      /> -->
      <div class="search">
      <Search class="barreR" />
    </div>
    </div>
    <div class="contenue">
      <ListeJeux class="listeJeux roundBorderSmall" />
      <div class="filtre glass roundBorderSmall">
        <FilterComponent :title="store_data.filter_title" />
      </div>
    </div>
    <div class="pagin">
      <!-- Pagination could be added here -->
    </div>
  </div>
</template>

<script setup>
import { reactive,  watch } from "vue";
import FilterComponent from "../components/store/FilterComponent.vue";
// Ensure Pagination is imported when you're ready to use it
// import Pagination from '../components/Pagination.vue';
// import SearchComponent from "../components/game/SearchComponent.vue";
import Search from '../components/game/Search.vue';
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
  searchQuery: String,
});

// // Example function to handle search (adjust as needed)
// const onSearchClick = () => {
//   if (store_data.searchQuery.trim()) {
//     console.log("Searching for:", store_data.searchQuery);
//   }
// };

// Optionally, watch the searchQuery for changes
watch(store_data.searchQuery, (newValue) => {
  console.log("Searching for:", newValue);
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
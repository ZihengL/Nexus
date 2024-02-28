<template>
  <div id="store">
    <div class="search">
      <Search class="barreR" />
    </div>
    <div class="contenue">
      <ListeJeux class="listeJeux roundBorderSmall" />

      <div class="filtre glass roundBorderSmall">
        <Filterr />
      </div>

    </div>
    <div class="pagin">
      <Pagination />
    </div>
  </div>
</template>

<script scoped>
import Filterr from '../components/store/Filter.vue';
// import Pagination from '../components/Pagination.vue';
import Search from '../components/game/Search.vue';
import ListeJeux from '../components/store/StoreComponent.vue';

export default {
  components: {
    ListeJeux,
    Search,
    Filterr,
    // Pagination
  },
  data() {
    return {
      searchQuery: '',
    };
  },
  methods: {
    // Define the method to handle icon click
    onSearchClick() {
      if (this.searchQuery.trim()) {
        // Implement your search logic here or call it if defined elsewhere
        console.log('Searching for:', this.searchQuery);
        // You can also add routing or other logic to perform the search
      }
    },
  },
  watch: {
    searchQuery(newValue) {
      // If you want to perform search on input change, keep this logic
      console.log('Searching for:', newValue);
    },
  },
};
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

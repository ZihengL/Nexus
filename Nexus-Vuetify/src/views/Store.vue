<template>
  <div id="store"> <!--flex colomn-->
    <div class="search"> <!--flex 1-->
      <Search class="barreR"/>
      <!--barre de recheche-->
    </div>
    <div class="contenue"> <!--flex 5--> <!--flex row-->
      <!--Boutique--> <!--flex 4-->
      <ListeJeux class="listeJeux"/>
      <!--filtre--> <!--flex 2-->
      <div class="filtre">
        <Filter/>
      </div>
    </div>
  </div>
</template>

<script scoped>
  import ListeJeux from '../components/StoreComponent.vue';
  import Search from '../components/Search.vue';
  import Filter from '../components/Filter.vue'
  
  export default {
    components: {
      ListeJeux,
      Search,
      Filter,
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
    margin: 5% auto 5% auto;
    border: 2px solid red;

    .search {
      flex: 1;
      text-align: right;
      justify-content: right;
      border: 2px solid red;
      margin-bottom: 1%;

      .barreR{
        width: 50%;

      }
    }
    .contenue {
      flex: 5;
      display: flex;
      gap: 2%;
      flex-direction: row;
      border: 2px solid red;
      
      .listeJeux {
        flex: 4;
      border: 2px solid red;
      }
      .filtre {
        flex: 2;
      border: 2px solid red;
      }
    }
  }
</style>

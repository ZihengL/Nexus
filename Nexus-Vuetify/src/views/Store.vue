<template>
  <div id="store"> <!--flex colomn-->
    <div class="search"> <!--flex 1-->
      <!--barre de recheche-->
    </div>
    <div class="contenue"> <!--flex 5--> <!--flex row-->
      <!--Boutique--> <!--flex 4-->
      <ListeJeux class="listeJeux"/>
      <!--filtre--> <!--flex 2-->
      <div class="filtre">
        filtre
      </div>
    </div>
  </div>
</template>

<script scoped>
  import Card from '../components/Card.vue';
  import ListeJeux from '../components/StoreComponent.vue';
  
  export default {
    components: {
      Card,
      ListeJeux,
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

    .search {
      flex: 1;
    }
    .contenue {
      flex: 5;
      display: flex;
      gap: 2%;
      flex-direction: row;
      
      .listeJeux {
        flex: 4;
      }
      .filtre {
        flex: 2;
      }
    }
  }
</style>

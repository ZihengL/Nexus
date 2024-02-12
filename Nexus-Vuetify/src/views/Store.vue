<template>
  <div class="store"> <!--flex colomn-->
    <div> <!--flex 1-->
      <!--barre de recheche-->
    </div>
    <div> <!--flex 5--> <!--flex row-->
      <!--Boutique--> <!--flex 4-->
      <!--filtre--> <!--flex 2-->
    </div>
  </div>
</template>

<script scoped>
  import Card from '../components/Card.vue';
  
  export default {
    components: {
      Card,
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

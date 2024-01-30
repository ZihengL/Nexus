<template>
    <v-card class="pa-3">
      <v-container>
        <v-row>
          <!-- Genres -->
          <v-col cols="12" md="3" class="pa-2">
            <v-select
              v-model="filters.genre"
              :items="genres"
              label="Genre"
              multiple
              outlined
              dense
              class="no-border-bottom"
            ></v-select>
          </v-col>
          <!-- Publishers/Developers -->
          <v-col cols="12" md="3" class="pa-2">
            <v-select
              v-model="filters.publisher"
              :items="publishers"
              label="studio de developpement"
              multiple
              outlined
              dense
              class="no-border-bottom"
            ></v-select>
          </v-col>
          <!-- Apply Filter Button -->
          <v-col cols="12" md="3" class="d-flex align-center pa-2">
            <v-btn color="primary" @click="applyFilters" class="ma-auto violet">Apply Filters</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </template>
  
  <script>
  export default {
    data() {
      return {
        filters: {
          genre: [],
          platform: [],
          publisher: []
        },
        genres: ['FPS', 'Adventure', 'Combat', 'Survival', 'Solo', 'Farming'],
        publishers: ['Electronic Arts', 'Activision', 'Ubisoft', 'Nintendo'],
      };
    },
    methods: {
      applyFilters() {
        // Emit an event with the selected filters
        this.$emit('filter-games', this.filters);
      }
    }
  };
  </script>
  
  <style scoped>
  .violet {
    color: blueviolet;
    background-color: blueviolet;
    color: white; /* Adjust text color if needed */
  }

  .no-border-bottom .v-input__slot {
    border-bottom: none;
  }
  </style>
  
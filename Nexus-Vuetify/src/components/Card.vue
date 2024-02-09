<template>
  <div id="Card">
    <v-card class="transparent-background pa-4">
      <v-text-field
        v-model="searchQuery"
        append-icon="mdi-magnify"
        label="Search Games"
        single-line
        hide-details
        @click:append="onSearchClick"
      ></v-text-field>
    </v-card>
    <!-- Filter Section -->
    <v-card class="pa-3 glass" style="border-radius: 0px;">
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
            <!--<v-btn color="primary" @click="applyFilters" class="ma-auto violet">Apply Filters</v-btn>-->

          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit glow" @click="toggleProfile">
              Filtrer
            </v-btn>
          </div>
            
          </v-col>
        </v-row>
      </v-container>
    </v-card>

    <v-container fluid class="card">
      <v-row>
        <v-col
          v-for="(game, index) in filteredGames"
          :key="index"
          cols="3"
          sm="6"
          md="4"
          lg="3"
        >
          <router-link to="/Game" class="unJeu">
            <v-img :src="game.image" aspect-ratio="1.7">
              <v-row class="fill-height ma-0" align="end" justify="start">
                <span class="discount" v-if="game.discount">{{ game.discount }}</span>
              </v-row>
            </v-img>
            <v-card-title>{{ game.title }}</v-card-title>
            <v-card-subtitle v-if="game.subtitle">{{ game.subtitle }}</v-card-subtitle>
            <v-card-text>
              <div class="price">{{ game.price }}</div>
            </v-card-text>
          </router-link>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>


<script scoped>export default {
  data() {
    return {
      games: [
          {
            title: 'Tekken 8',
            subtitle: null,
            image: '../src/assets/img/tekken.png',
            price: 'C$67.69',
            Genre: 'Combat'
          },
          {
            title: 'Ready or Not',
            subtitle: null,
            image: '../src/assets/img/zomboid.png',
            price: 'C$53.99',
            Genre: 'FPS'
          },
          {
            title: 'Palworld',
            subtitle: null,
            image: '../src/assets/img/palworld.png',
            price: 'C$50.73',
            Genre: 'Adventure'
          }, 
          {
            title: 'Apex',
            subtitle: null,
            image: '../src/assets/img/apex.png',
            price: 'Gratuit',
            Genre: 'FPS'
          }, 
          {
            title: 'dontstarve',
            subtitle: null,
            image: '../src/assets/img/dontstarve.png',
            price: 'C$67.69',
            Genre: 'Survival'
          },
          {
            title: 'satisfactory',
            subtitle: null,
            image: '../src/assets/img/satisfactory.png',
            price: 'C$80.69',
            Genre: 'Solo'
          },
          {
            title: 'Solo Leveling Arise',
            subtitle: null,
            image: '../src/assets/img/solo_leveling.png',
            price: 'Gratuit',
            Genre: 'Farming',
          }
      ],
      searchQuery: '',
      genres: ['Combat', 'FPS', 'Adventure', 'Survival', 'Solo', 'Farming'], // Example genres
      publishers: ['Publisher A', 'Publisher B', 'Publisher C'], // Example publishers
      filters: {
        genre: [],
        publisher: [],
      },
    };
  },
  computed: {
    filteredGames() {
      let filtered = this.games;
      // Filter by searchQuery
      if (this.searchQuery.trim()) {
        filtered = filtered.filter((game) =>
          game.title.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      // Additional filtering logic here based on `filters.genre` and `filters.publisher`
      return filtered;
    },
  },
  methods: {
    onSearchClick() {
      console.log('Searching for:', this.searchQuery);
    },
    applyFilters() {
      // Implement the logic to apply filters here
      console.log('Applying filters:', this.filters);
    },
  },
};

</script>
<style scoped>

.transparent-background {
  background-color: transparent;
  box-shadow: none; /* Removes shadow */
  border: none; /* Removes border */
}

/* Previously targeted v-text-field */
.v-text-field .v-input__slot:before,
.v-text-field .v-input__slot:after {
  border-bottom: none !important;
}

.v-text-field--underline::after,
.v-text-field--underline::before {
  background-color: transparent !important;
}

/* Targeting the v-select underline for transparency */
.no-border-bottom .v-input__slot:after,
.no-border-bottom .v-input__slot:before {
  background-color: transparent !important;
  border-bottom: none !important; /* In case Vuetify uses a border */
}

/* Additional targeting for v-select specifically if the above doesn't work */
.v-select.no-border-bottom .v-input__slot:after,
.v-select.no-border-bottom .v-input__slot:before {
  background-color: transparent !important;
  border-bottom: none !important;
}
.pa-3 {
  width: 70%;
  margin: auto;
}
.pa-4 {
  width: 71.8%;
  margin: auto;
}
.card {
  width: 72%;
}
.unJeu {
  text-decoration: none;
  color: black;
  display: block;
  background-color: var(--light-gray-trans);
}
.unJeu:hover {
  background-color: rgba(128, 128, 128, 0.083);
}
</style>

<template>
    <v-carousel 
      cycle 
      hide-delimiters 
      :interval="5000"
    >
      <v-carousel-item 
        v-for="(item, i) in items" 
        :key="i"
      >
        <v-img :src="item.src">
          <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
              <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
            </v-row>
          </template>
  
          <!-- Navigation bar -->
          <v-toolbar 
            color="rgba(0, 0, 0, 0.5)" 
            dark 
            absolute 
            bottom
          >
            <v-toolbar-title>{{ item.title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="prev">
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-btn icon @click="next">
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
          </v-toolbar>
        </v-img>
      </v-carousel-item>
    </v-carousel>
  </template>
  
  <script>
  export default {
    data () {
      return {
        items: [
          { src: '../src/assets/img/zomboid.png' },
          { src: '../src/assets/img/elden_ring.png' },
          { src: '../src/assets/img/palworld.png' },
          // Add more images as needed
        ]
      }
    },
    methods: {
      next() {
        this.$refs.carousel.next();
      },
      prev() {
        this.$refs.carousel.prev();
      }
    }
  }
  </script>
  
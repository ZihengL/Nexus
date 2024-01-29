<template>
    <v-carousel 
      cycle 
      hide-delimiters 
      :interval="5000" 
      class="carousel"
    >
      <v-carousel-item 
        v-for="(item, i) in items" 
        :key="i"
        :src="item.src" 
      ></v-carousel-item>
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
  <style scoped>
  .carousel{
    border-top: 100px;
  }
  </style>
  
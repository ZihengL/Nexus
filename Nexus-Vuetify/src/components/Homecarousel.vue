<template>
  <v-carousel 
    cycle 
    height="400" 
    show-arrows-on-hover
  >
    <v-carousel-item v-for="(item, i) in items" :key="i">
      <v-img :src="item.src"></v-img>
    </v-carousel-item>
  </v-carousel>
</template>

<script>
export default {
  data: () => ({
    items: [
      { src: '../src/assets/img/palworld.png' },
      { src: '../src/assets/img/elden_ring.png' },
      { src: '../src/assets/img/zomboid.png' },
      { src: '../src/assets/img/tekken.png' },
      // Add as many images as you want
    ],
  }),
};
</script>

<style scoped>
body {
  background-color: blue;
}
.carousel {

}
.title-page {
  font-size: 75px;
  color: blue;
  margin-top: 1%;
  text-align: center;
  font-family: 'Times New Roman', Times, serif;
}
</style>

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
      { src: '../src/assets/img/tekken.png' },
      { src: '../src/assets/img/zomboid.png' },
      // Add as many images as you want
    ],
  }),
};
</script>

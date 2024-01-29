<template>
  <v-carousel 
    hide-delimiters
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
      { src: './src/assets/BG_IMG/img2.jpg' },
      { src: './src/assets/BG_IMG/logo_shop.png' },
      // Add as many images as you want
    ],
  }),
};
</script>

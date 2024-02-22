<template>
  <div>
    <!-- carousel -->
    <div class="carousel">
      <!-- list item -->
      <div class="list">
        <div v-for="(item, index) in carouselItems" :key="index" class="item">
          <div class="imgContainer">
            <div class="overlayGrad"></div>
            <img :src="item.image">
          </div>
          <div class="content">
            <div :class="index == 0 ? 'title' : 'lostTitle'">{{ item.title }}</div>
            <div :class="index == 0 ? 'buttons' : 'lostBouttons'">
              <router-link :to="{ name: 'Game', params: { idGame: item.id } }" class="btn roundBorderSmall">Voir le jeu</router-link>
              <router-link :to="{ name: 'Dev', params: { idDevl: item.developerID } }" class="btn roundBorderSmall">Decouvrir le developeur</router-link>
            </div>
          </div>
        </div>
      </div>
      <!-- list thumbnail -->
      <div class="thumbnail">
        <div v-for="(item, index) in carouselItems" :key="index" class="item">
          <img :src="item.image" class=" roundBorderSmall">
          <div class="content">
            <div class="title">{{ item.title }}</div>
          </div>
        </div>
      </div>
      <!-- next prev -->
      <div class="arrows">
        <button @click="showSlider('prev')"><</button>
        <button @click="showSlider('next')">></button>
      </div>
      <!-- time running -->
      <div class="time"></div>
    </div>
  </div>
</template>
<script src="../JS/carrouselScript.js">

</script>
<style src="../styles/HomeCarousselStyle.scss" scoped></style>
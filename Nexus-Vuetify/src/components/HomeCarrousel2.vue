<template>
    <div>
      <!-- carousel -->
      <div class="carousel">
        <!-- list item -->
        <div class="list">
          <div v-for="(item, index) in carouselItems" :key="index" class="item">
            <img :src="item.image">
            <div class="content">
              <div class="title">{{ item.title }}</div>
              <button>SUBSCRIBE</button>
            </div>
          </div>
        </div>
        <!-- list thumbnail -->
        <div class="thumbnail">
          <div v-for="(item, index) in carouselItems" :key="index" class="item">
            <img :src="item.image">
            <div class="content">
              <div class="title">{{ item.thumbnailTitle }}</div>
              <div class="description">{{ item.thumbnailDescription }}</div>
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
<style src="../styles/CarousselStyle.css" scoped></style>
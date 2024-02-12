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
            <div class="buttons">
              <router-link to="/Game" class="btn">Voir le jeu</router-link>
              <router-link to="/Dev" class="btn">Decouvrir le developeur</router-link>
            </div>
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
var tabCarousell = []; 
/*const tabCarousell = [
  {id: 1, image: '../assets/3f3rg', title: 'titre', description: 'fweg3rg'},
  {id: 2, image: '../assets/3f3rg', title: 'titre', description: 'fweg3rg'},
  {id: 4, image: '../assets/3f3rg', title: 'titre', description: 'fweg3rg'},
];*/
const account = {
  id: this.id,
  image: this.image,
  title: this.title,
  description: this.description
};

  const url = "https://localhost:4208/nexus/BackEnd/getAllProducts";
    fetch(url, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(account),
    })
    .then(response => {
      if (response.status !== 200) {
        console.log('Error: Non-200 status code');
        return [];
      }
      // console.log(response.text())
      return response.json();
    }).then(data => {
      
      tabCarousell = this.processFetchedData(data)
    })
    .catch(error => console.log(error));
console.log(tabCarousell);
</script>
<style src="../styles/HomeCarousselStyle.scss" scoped></style>
<template>
    <div class="slider">
      <div class="list" ref="sliderList">
        <div v-for="(imgGame, index) in tabImgGame" :key="index" class="item">
            <img :src="imgGame" alt="#">
        </div>
      </div>
      <div class="buttons">
        <button id="prev" @click="prevFunc">&lt;</button>
        <button id="next" @click="nextFunc">&gt;</button>
      </div>
      <ul class="dots">
        <li :class="{ active: active === index }" v-for="(dot, index) in tabImgGame" :key="index" @click="goToSlide(index)"></li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue';
  const tabImgGame = [
	"./src/assets/image/img1.jpg",
	"./src/assets/image/img2.jpg",
	"./src/assets/image/img3.jpg",
	"./src/assets/image/img1.jpg",
	"./src/assets/image/img4.jpg",
  ];
  const sliderList = ref(null);
  const items = ref(null);
  const next = ref(null);
  const prev = ref(null);
  const dots = ref(null);
  
  let lengthItems = 0;
  let active = 0;
  let refreshInterval;
  
  onMounted(() => {
    items.value = sliderList.value.querySelectorAll('.item');
    lengthItems = items.value.length - 1;
    next.value = document.getElementById('next');
    prev.value = document.getElementById('prev');
    dots.value = sliderList.value.querySelectorAll('.dots li');
  
    refreshInterval = setInterval(() => {
      next.value.click();
    }, 3000);
  });
  
  onBeforeUnmount(() => {
    clearInterval(refreshInterval);
  });
  
  const nextFunc = () => {
    active = active + 1 <= lengthItems ? active + 1 : 0;
    reloadSlider();
  };
  
  const prevFunc = () => {
    active = active - 1 >= 0 ? active - 1 : lengthItems;
    reloadSlider();
  };

const reloadSlider = () => {
    sliderList.value.style.left = -items.value[active].offsetLeft + 'px';

    let lastActiveDot = sliderList.value.querySelector('.dots li.active');
    if (lastActiveDot) {
        lastActiveDot.classList.remove('active');
    }
    
    dots.value[active].classList.add('active');

    clearInterval(refreshInterval);
    refreshInterval = setInterval(() => {
        next.value.click();
    }, 3000);
};

  
  const goToSlide = (index) => {
    active = index;
    reloadSlider();
  };
  </script>

  
<style>
  .slider{
    width: 1300px;
    max-width: 100vw;
    height: 700px;
    margin: auto;
    position: relative;
    overflow: hidden;
  }
  .slider .list{
      position: absolute;
      width: max-content;
      height: 100%;
      left: 0;
      top: 0;
      display: flex;
      transition: 1s;
  }
  .slider .list img{
      height: 100%;
      object-fit: cover;
  }
  .slider .buttons{
      position: absolute;
      top: 45%;
      left: 5%;
      width: 90%;
      display: flex;
      justify-content: space-between;
  }
  .slider .buttons button{
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #fff5;
      color: #fff;
      border: none;
      font-family: monospace;
      font-weight: bold;
  }
  .slider .buttons button:hover {
      background-color: rgba(255, 255, 255, 0.72);
  }
  .slider .dots{
      position: absolute;
      bottom: 10px;
      left: 0;
      color: #000000;
      width: 100%;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
  }
  .slider .dots li{
      list-style: none;
      width: 10px;
      height: 10px;
      background-color: #ffffff;
      margin: 10px;
      border-radius: 20px;
      transition: 0.5s;
  }
  .slider .dots li:hover {
    background-color: #ffffff8d;
  }
  .slider .dots li.active{
      width: 30px;
  }
  @media screen and (max-width: 768px){
      .slider{
          height: 400px;
      }
  }
</style>

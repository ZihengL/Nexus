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
	"/src/assets/image/img1.jpg",
	"/src/assets/image/img2.jpg",
	"/src/assets/image/img3.jpg",
	"/src/assets/image/img1.jpg",
	"/src/assets/image/img4.jpg",
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

  
<style src="../../styles/GameCarrouselStyle.scss"></style>

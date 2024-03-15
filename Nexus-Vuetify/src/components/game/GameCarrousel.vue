<template>
  <div v-if="tabImgGame.length > 0" class="slider">
    <div class="list" ref="sliderList">
      <div v-for="(media, index) in tabImgGame" :key="index" class="item">
        <img v-if="['png', 'jpg', 'jpeg', 'image'].includes(media.type)" :src="media.src" :alt="`Image ${index}`">
        <video v-else controls>
          <source :src="media.src" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
    <div class="buttons">
      <button id="prev" @click="prevFunc" class="glow2">&lt;</button>
      <button id="next" @click="nextFunc" class="glow2">&gt;</button>
    </div>
    <ul class="dots">
      <li :class="{ active: active === index }" v-for="(dot, index) in tabImgGame" :key="index" @click="goToSlide(index)" class="glow2"></li>
    </ul>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref as vueRef, nextTick, defineProps } from 'vue';
import { getStorage, ref as firebaseRef, getDownloadURL , getMetadata} from "firebase/storage";

const storage = getStorage();

const props = defineProps({
  idJeux: {
    type: Number,
  },
})

const defaultPath = "/src/assets/image/img1.png";
const tabImgGame = vueRef([]);
const sliderList = vueRef(null);

let active = vueRef(0);
let lengthItems = 0;
let refreshInterval;



async function fetchCarouselGameImages(gameId, index) {
  const imagePath = `Games/${gameId}/media/${gameId}_${index}.png`;
  const imageRef = firebaseRef(storage, imagePath);
  try {
    const url = await getDownloadURL(imageRef);
    console.log("Firebase URL image: ", url);

    const metadata = await getMetadata(imageRef); 
    console.log("Metadata: ", metadata);

    tabImgGame.value.push({
      id: gameId,
      src: url,
      type: metadata.contentType.startsWith('image/') ? 'image' : 'video',
      size: metadata.size,
      contentType: metadata.contentType,
    });
  } catch (error) {
    console.error(`Error fetching image for ${gameId}:`, error);
    tabImgGame.value.push({ id: gameId, src: defaultPath, type: 'image' });
  }
}

onMounted(async () => {
  for (let index = 1; index <= 4; index++) {
    await fetchCarouselGameImages(props.idJeux, index);
  }
  if (tabImgGame.value.length > 0) {
    await nextTick(); // Ensure the DOM is updated
    lengthItems = sliderList.value.querySelectorAll('.item').length - 1;

    refreshInterval = setInterval(() => {
      nextFunc();
    }, 3000);
  }
});

onBeforeUnmount(() => {
  clearInterval(refreshInterval);
});

const nextFunc = () => {
  active.value = active.value + 1 <= lengthItems ? active.value + 1 : 0;
  reloadSlider();
};

const prevFunc = () => {
  active.value = active.value - 1 >= 0 ? active.value - 1 : lengthItems;
  reloadSlider();
};

const reloadSlider = () => {
  const items = sliderList.value.querySelectorAll('.item');
  const dots = document.querySelectorAll('.dots li');

  sliderList.value.style.transform = `translateX(-${items[active.value].offsetLeft}px)`;

  const lastActiveDot = document.querySelector('.dots li.active');
  if (lastActiveDot) {
    lastActiveDot.classList.remove('active');
  }

  if (dots.length > 0) {
    dots[active.value].classList.add('active');
  }

  clearInterval(refreshInterval);
  refreshInterval = setInterval(() => {
    nextFunc();
  }, 3000);
};

const goToSlide = (index) => {
  active.value = index;
  reloadSlider();
};
</script>

<style src="../../styles/GameCarrouselStyle.scss"></style>

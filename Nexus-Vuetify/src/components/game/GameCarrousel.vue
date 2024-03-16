<template>
  <div v-if="tabImgGame.length > 0" class="slider">
    <div class="list" ref="sliderList">
      <div v-for="(media, index) in tabImgGame" :key="index" class="item">
        <img
          v-if="media.type === 'image'"
          :src="media.src"
          :alt="`Image ${index}`"
        />
        <video v-else controls @play="handleVideoPlay" @ended="handleVideoEnd">
          <source :src="media.src" type="video/mp4" />
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
    <div class="buttons">
      <button id="prev" @click="prevFunc" class="glow2">&lt;</button>
      <button id="next" @click="nextFunc" class="glow2">&gt;</button>
    </div>
    <ul class="dots">
      <li
        v-for="(dot, index) in tabImgGame"
        :key="index"
        :class="{ active: active === index }"
        @click="() => goToSlide(index)"
        class="glow2"
      ></li>
    </ul>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref, nextTick, defineProps } from "vue";
import {
  getStorage,
  ref as firebaseRef,
  getDownloadURL,
  getMetadata,
  listAll,
} from "firebase/storage";

const storage = getStorage();

const props = defineProps({
  idJeux: Number,
});

const tabImgGame = ref([]);
const sliderList = ref(null);
let active = ref(0);
let lengthItems = 0;
let refreshInterval;

const fetchAllGameMedia = async (gameId) => {
  const mediaPath = `Games/${gameId}/media`;
  const mediaRef = firebaseRef(storage, mediaPath);

  try {
    const mediaList = await listAll(mediaRef);
    for (const itemRef of mediaList.items) {
      if (
        !itemRef.name.endsWith("_Store.png") &&
        !itemRef.name.endsWith(`${gameId}_0.png`)
      ) {
        const url = await getDownloadURL(itemRef);
        const metadata = await getMetadata(itemRef);

        tabImgGame.value.push({
          id: gameId,
          src: url,
          type: metadata.contentType.startsWith("image/") ? "image" : "video",
        });
      }
    }
  } catch (error) {
    console.error(`Error listing media for ${gameId}:`, error);
  }
};

const reloadSlider = () => {
  const items = sliderList.value.querySelectorAll(".item");
  const dots = document.querySelectorAll(".dots li");

  sliderList.value.style.transform = `translateX(-${
    items[active.value].offsetLeft
  }px)`;

  dots.forEach((dot) => dot.classList.remove("active"));
  if (dots.length > 0) {
    dots[active.value].classList.add("active");
  }

  resetCarouselInterval();
};

const nextFunc = () => {
  active.value = active.value + 1 <= lengthItems ? active.value + 1 : 0;
  reloadSlider();
};

const prevFunc = () => {
  active.value = active.value - 1 >= 0 ? active.value - 1 : lengthItems;
  reloadSlider();
};

const goToSlide = (index) => {
  active.value = index;
  reloadSlider();
};

const resetCarouselInterval = () => {
  clearInterval(refreshInterval);
  refreshInterval = setInterval(() => {
    nextFunc();
  }, 3000);
};

const handleVideoPlay = () => {
  clearInterval(refreshInterval);
};

const handleVideoEnd = () => {
  setTimeout(() => {
    resetCarouselInterval();
  }, 5000); // Wait for 5 seconds after video ends to resume the carousel
};

onMounted(async () => {
  await fetchAllGameMedia(props.idJeux);
  await nextTick(); // Ensure the DOM is updated after fetching media
  lengthItems = sliderList.value.querySelectorAll(".item").length - 1;
  resetCarouselInterval();
});

onBeforeUnmount(() => {
  clearInterval(refreshInterval);
});
</script>
<style scoped>
.slider {
  width: 1300px;
  max-width: 100vw;
  height: 60svh;
  margin: auto;
  position: relative;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5);

  .list {
    /* position: absolute;
        width: max-content;
        width: 400%;
        height: 100%;
        left: 0;
        top: 0; */
    display: flex;
    transition: transform 1s ease;
    /* transition: 1s; */

    .item {
      flex: 0 0 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .item img,
    .item video {
      max-width: 100%;
      max-height: 60vh;
      object-fit: contain;
    }
  }
  .buttons {
    position: absolute;
    top: 45%;
    left: 5%;
    width: 90%;
    display: flex;
    justify-content: space-between;

    button {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #fff5;
      color: #fff;
      border: none;
      font-family: monospace;
      font-weight: bold;
    }
    button:hover {
      background-color: rgba(255, 255, 255, 0.72);
    }
  }
  .dots {
    margin-top: 50%;
    position: relative;
    bottom: 8%;
    left: 0;
    color: #000000;
    width: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.5);

    li {
      list-style: none;
      width: 10px;
      height: 10px;
      background-color: #ffffff;
      margin: 10px;
      border-radius: 20px;
      transition: 0.5s;
    }
    li:hover {
      background-color: #ffffff8d;
    }
    li.active {
      width: 30px;
    }
  }
}
@media screen and (max-width: 768px) {
  .slider {
    height: 400px;
  }
}
</style>

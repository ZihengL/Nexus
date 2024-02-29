<template>
  <router-link
    v-if="leGame && devName"
    :to="{ name: 'Game', params: { idGame: leGame.id } }"
    class="glass2 roundBorderSmall"
  >
    <div class="img roundBorderSmall">
      <img
        :src="leGame.value.image || '../../assets/img/dontstarve.png'"
        :alt="nothingBro"
        :class="roundBorderSmall"
      />
      <p class="roundBorderSmall">
        {{ leGame.description }}
      </p>
    </div>
    <h3>{{ leGame.title }}</h3>
    <h4>{{ devName }}</h4>
    <div class="ratings">
      <v-rating
        hover
        half-increments
        :length="5"
        :size="32"
        :model-value="leGame.ratingAverage"
        active-color="rgba(3,33,76,1)"
        class="rat"
      />
    </div>
    <ul>
      <li class="glow">Tag</li>
      <li class="glow">Tag</li>
      <li class="glow">Tag</li>
    </ul>
  </router-link>
</template>

<script setup>
import { fetchData } from "../../JS/fetch";
import { ref, onMounted } from "vue";

const props = defineProps(["idGame"]);
let leGame = ref(null);
let devName = ref(null);

async function getGameInfos() {
  const dataGame = await fetchData(
    "games",
    "getOne",
    "id",
    props.idGame,
    null,
    null,
    null,
    "GET"
  );

  leGame.value = dataGame;
  console.log("leGame : ", leGame.value);

  if (leGame.value) {
    const devId = leGame.value.developerID;
    console.log("devId : ", devId);

    const filters = {
      id: devId,
    };
    const sorting = {
      id: false,
    };

    const includedColumns = ["id", "username"];
    const jsonBody = { filters, sorting, includedColumns };

    const dataDevs = await fetchData(
      "users",
      "getAllMatching",
      null,
      null,
      null,
      null,
      jsonBody,
      "POST"
    );
    devName.value = dataDevs;
    console.log("devs : ", devName);
  }
}

onMounted(async () => {
  await getGameInfos();
});
</script>

<style lang="scss">
.glass2 {
  text-decoration: none;
  color: var(--light-trans-2);
  padding-bottom: 2%;
  .img {
    flex: 5;
    position: relative;

    img {
      width: 100%;
      display: inline-block;
      transition: opacity 0.3s ease; /* Ajout de la transition d'opacité */
      opacity: 1; /* L'image est initialement complètement opaque */
    }

    p {
      display: none;
      position: absolute;
      text-align: center;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 2; /* Assure que le texte apparaît au-dessus de l'image */
    }
  }

  ul {
    display: flex;
    justify-content: space-around;
    flex-direction: row;
    list-style: none;
  }
}
.glass2:hover {
  .img img {
    opacity: 0.3; /* Réduit l'opacité de l'image lorsqu'elle est survolée */
  }

  .img p {
    display: inline-block;
    width: 90%;
  }
}
</style>

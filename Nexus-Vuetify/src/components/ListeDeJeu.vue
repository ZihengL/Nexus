<template>
  <!-- <div v-for="activity in activities" :key="activity.id" class="activities">-->
  <div v-if="LeGame" class="container glass3 glow  roundBorderSmall">
    <div class="img">
      <img src="../assets//img/apex.png" alt="image jeu" class=" roundBorderSmall">
    </div>
    <div class="jeu">
      <span v-if="props.himself">Joué à {{ LeGame.title }} le 17/04/2022</span>
      <span v-else>{{ LeGame.title }}</span>
      <br />
      <div class="fieldBtn">
        <div class="btn-layer"></div>
        <v-btn :to="{ name: 'Game', params: { idGame: props.idJeu } }" density="default" class="submit glow">
          Voir plus
        </v-btn>
      </div>
    </div>
  </div>
  <div class="pagination">
    <a href="#" class="prev">&laquo; Previous</a>
    <a href="#" class="active">1</a>
    <a href="#">2</a>
    <a href="#">3</a>
    <a href="#">4</a>
    <a href="#">5</a>
    <a href="#" class="next">Next &raquo;</a>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref } from 'vue';
import { fetchData } from '../JS/fetch';
const props = defineProps(['himself', 'idJeu']);
const LeGame = ref(null);

onMounted(async () => {
  try {
    const dataGame = await fetchData("games", "getOne", "id", props.idJeu, null, "GET");
    LeGame.value = dataGame;
    //console.log('leDevs : ', leDevs)

  } catch (error) {
    console.error('Error fetching data:', error);
  }
});
</script>

<style lang="scss">
.pagin {
  margin-top: 20%;
  border: 1px solid red;
}

.container {
  display: flex;
  justify-content: space-between;
  border: 1px solid rgba(0, 0, 0, 0.132);
  padding: 20px;
  margin-top: 1.5%;
  gap: 40%;

}

.img {
  flex: 3;
  align-self: flex-start;
  width: 10%;

  img {
    width: 100%;
    //display: none;
    //width: 1rem;
  }
}

.jeu {
  flex: 4;
  margin-left: 40px;
  align-self: flex-end;
  justify-content: space-around;
}
</style>

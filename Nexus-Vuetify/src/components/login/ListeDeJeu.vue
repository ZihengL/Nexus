<template>
  <!-- <div v-for="activity in activities" :key="activity.id" class="activities">-->
  <div v-if="LeGame" class="container glass2 roundBorderSmall">
    <div class="up">
      <div class="img">
        <img src="../../assets//img/apex.png" alt="image jeu" class=" roundBorderSmall">
      </div>
      <div class="jeu">
        <span v-if="props.himself && !props.buy">{{ LeGame}} : televerser le 17/04/2022 </span>
        <span v-if="props.himself && props.buy">Joué à {{ LeGame}} le 17/04/2022</span>
        <span v-else>{{ LeGame }}</span>
        <br />
      </div>
    </div>
    <div class="listeBtn">
        <div class="fieldBtn">
          <div class="btn-layer"></div>
          <v-btn :to="{ name: 'Game', params: { idGame: props.idJeu } }" density="default" class="submit glow">
            Voir plus
          </v-btn>
        </div>
        <btnComp v-if="props.himself"  :contenu="'Supprimmer'" @toggle-btn="'methode'" />
        <btnComp v-if="props.himself" :contenu="'Mettre a jour'" @toggle-btn="'methode'" />
      </div>
  </div>

</template>

<script setup>
import btnComp from "../btnComponent.vue";
import { defineProps, onMounted, ref } from 'vue';
import { fetchData } from '../../JS/fetch';
const props = defineProps(['himself', 'idJeu', 'buy']);
const LeGame = ref(null);

onMounted(async () => {
  try {
    const dataGame = await fetchData("games", "getOne", "id", props.idJeu,null, null, null, "GET");
    LeGame.value = dataGame[0].title;
    console.log('leDevs : ', dataGame)

  } catch (error) {
    console.error('Error fetching data:', error);
  }
});
</script>

<style lang="scss">
.container {
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(0, 0, 0, 0.132);
  margin-top: 1.5%;

  .up {
    display: flex;
    flex-direction: row;
    justify-content: space-between;

    .img {
      flex: 2;
      

      img {
        width: 100%;
      }
    }

    .jeu {
      flex: 5;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 0%;
    }
  }
  .listeBtn {
    display: flex;
    flex-direction: row;
    gap: 3%;
    padding: 0% 1% 2% 1%;
  }
}

</style>

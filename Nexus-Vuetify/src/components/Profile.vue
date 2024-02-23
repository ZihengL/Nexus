<template>
  <!-- Created By CodingNepal -->
  <div v-if="leDevs" class="allP">
    <div class="containerProfile" :class="isHimself ? 'container1' : 'container2'">
      <div class="wrapper">
        <div class="description  glass roundBorderSmall" >
          <div :class="isHimself ? 'imgContainerFull' : 'imgContainer'">
            <img src="../assets/Rich_Ricasso.png" alt="John" class="imgProfil" />
          </div>
          <div class="champUtilisateur">
            <h3>{{ leDevs.user }}</h3>
            <br>
            <p>description</p>
          </div>
          <div class="button"  v-show="isHimself">
            <router-link :to="{ name: 'Profile', params: { IdDev: props.idDevl } }" class="router glow">
              <v-icon icon="mdi-account-circle" />
              <span class="link-btn">Gerer son profil</span>
            </router-link>

            <div class="fieldBtn">
              <div class="btn-layer"></div>
              <v-btn density="default" class="submit glow" @click="$emit('showLogin')">
                Se deconnecter
              </v-btn>
            </div>
          </div>
        </div>
        <div style="display: flex; margin-top: 1.5%;" class=" glass roundBorderSmall">
          <div class="laListeJeu">
            <h2>Liste de jeu</h2>
            <liste-de-jeu />
            <liste-de-jeu />
            <liste-de-jeu />
          </div>
        </div>
      </div>
    </div>
    <div v-if="isHimself" class="listeFriends glass roundBorderSmall">
      <h2> liste amis</h2>
      <amis />
      <amis />
      <amis />
    </div>
  </div>
</template>


<script setup>
import ListeDeJeu from './ListeDeJeu.vue';
import { defineProps, ref, onMounted } from 'vue';
import Amis from './amis.vue';
import { fetchData } from '../JS/fetch';

const props = defineProps(['isHimself', 'idDevl']);

console.log(props);
const leDevs = ref(null);

onMounted(async () => {
    try {
      const dataGame = await fetchData("users", "getOne", "id", props.idDevl, null, "GET");
      leDevs.value = dataGame;
      //console.log('leDevs : ', leDevs)
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

</script>


<style src="../styles/ProfileStyle.scss"></style>

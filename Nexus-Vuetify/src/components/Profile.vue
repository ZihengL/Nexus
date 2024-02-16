<template>
  <!-- Created By CodingNepal -->
  <div class="allP">
    <div class="containerProfile" :class="isHimself ? 'container1' : 'container2'">
      <div class="wrapper">
        <div class="description  glass roundBorderSmall" >
          <div :class="isHimself ? 'imgContainerFull' : 'imgContainer'">
            <img src="../assets/Rich_Ricasso.png" alt="John" class="imgProfil" />
          </div>
          <div class="champUtilisateur">
            <h3>Nom Prenom</h3>
            <br>
            <p>description</p>
          </div>
          <div class="button"  v-show="isHimself">
            <router-link to="/Profile" class="router glow">
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
  const props = defineProps(['isHimself']);
  console.log(props.isHimself);

import Amis from './amis.vue';

</script>

<style src="../styles/ProfileStyle.scss"></style>

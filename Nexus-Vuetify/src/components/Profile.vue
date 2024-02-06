<template>
  <!-- Created By CodingNepal -->
  <div class="container">
    <div class="wrapper">
      <div class="description">
        <img src="../assets/Rich_Ricasso.png" alt="John" class="imgProfil"/>
        <div class="champUtilisateur">
          <h3>Nom Prenom</h3>
          <br>
          <p>description</p>
        </div>
        <div class="button">
          <router-link to="/Profile" class="router">
            <v-icon icon="mdi-account-circle" />
            <span class="link-btn">Gerer son profil</span>
          </router-link>
          <div class="field">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" @click="$emit('showLogin')">
              Se deconnecter
            </v-btn>
          </div>

        </div>
      </div>
      <div>
        <h2>Liste de jeu</h2>
        <liste-de-jeu />
        <liste-de-jeu />
        <liste-de-jeu />
        <liste-de-jeu />
        <liste-de-jeu />
        <liste-de-jeu />
      </div>
    </div>

  </div>
</template>


<script setup>
import ListeDeJeu from './ListeDeJeu.vue';

</script>

<style src="../styles/ProfileStyle.scss"></style>

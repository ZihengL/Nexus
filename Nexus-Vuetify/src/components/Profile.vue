<template>
  <!-- Created By CodingNepal -->
  <div style="display: flex;flex-direction: row; ">
    <div class="wrapper" style="flex-basis: 100%;">
      <div style="display: flex;">
        <img src="../assets/Rich_Ricasso.png" alt="John" height="200px"></img>
        <div style="display: flex;text-align: left; flex-direction:  column; margin-left: 15px;">
          <h3>Nom Prenom</h3>
          <br>
          <p>description</p>
        </div>

      </div>
      <div>
        <h2>Activité Récente</h2>
        <liste-de-jeu />
        
      </div>
    </div>
    <div class="wrapper" style="flex-basis: 50%;">
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
</template>


<script setup>
import ListeDeJeu from './ListeDeJeu.vue';

</script>

<style src="../styles/ProfileStyle.scss"></style>

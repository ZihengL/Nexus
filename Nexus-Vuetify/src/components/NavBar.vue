<template>
  <nav class="navBar">
    <div class="navContent">
      <router-link to="/" class="logo" @mouseover="changeUrl" @mouseleave="rechangeUrl">
        <img :src="hover ? logo2URL : logoURL" alt="Vue" />
      </router-link>
      <div class="link">
        <v-spacer></v-spacer>
        <router-link to="/" class="router glow">
          <span class="link-btn" text>Accueil</span>
        </router-link>
        <router-link to="/Store" class="router glow">
          <span class="link-btn" text>Boutique</span>
        </router-link>
        <router-link to="/About" class="router glow">
          <span class="link-btn">A propos</span>
        </router-link>
        <v-spacer></v-spacer>
        <router-link to="/Login" class="router glow"> <!-- v-if="!$isConnected"-->
          <span class="link-btn" text>Connexion</span>
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';

let hover = ref(false);
let logoURL = '/src/assets/logos/Nexus_c5c3c0.svg';
let logo2URL = '/src/assets/logos/Nexus_171d25.svg';

const changeUrl = () => {
  console.log('changeUrl called');
  hover.value = true;
};

const rechangeUrl = () => {
  console.log('rechangeUrl called');
  hover.value = false;
};

// Accédez directement à la propriété globale sans utiliser "this"
//console.log('var glob : ', $isGlobalVariable);
</script>



<style src="../styles/NavBarStyle.scss" scoped></style>

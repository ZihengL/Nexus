<template>
    <nav class="navBar ">
      <div class="navContent">
        <router-link to="/"  class="logo">
                <img :src="logoURL" alt="Vue" />
              </router-link>
        <div class="link">
          <v-spacer></v-spacer>
          <router-link to="/"  class="router glow">
            <span class="link-btn" text>Accueil</span>
          </router-link>
          <router-link to="/Store"  class="router glow">
            <span class="link-btn" text>Boutique</span>
          </router-link>
          <router-link to="/About"  class="router glow">
            <span class="link-btn" >A propos</span>
          </router-link>
          <v-spacer></v-spacer>
          <router-link to="/Login" class="router" v-if="!isConnected.value">
                  <span class="link-btn" text>Connexion</span>
          </router-link>
          <router-link to="/Login" class="router" v-else>
            <v-icon icon="mdi-account-circle" class="conectedIcon"/>
          </router-link>
        </div>

      </div>
    </nav>
</template>


<script setup>
  import { ref } from 'vue';
  import logoURL from '/src/assets/logoNexus.png'



  // Récupérer la valeur depuis le stockage local
  const storedIsConnected = localStorage.getItem('isConnected');

  // Utiliser la valeur du stockage local ou attribuer false par défaut
  var isConnected = ref(storedIsConnected !== null ? storedIsConnected : false);
  //isConnected.value = true
  console.log("nav " + isConnected.value);
  localStorage.setItem("isConnected", isConnected.value.toString());
  //var isConnected = ref(localStorage.getItem("isConnected") === "false")*/

</script>

<style src="../styles/NavBarStyle.scss" scoped></style>

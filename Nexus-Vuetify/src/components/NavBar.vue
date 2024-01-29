<template>
    <v-app-bar :elevation="21" rounded>
      <div class="logo">
				<img :src="logoURL" alt="Vue" />
			</div>
      <div class="link">
        <v-spacer></v-spacer>
        <router-link to="/"  class="betweenLinks router">
          <v-icon icon="mdi-home" />
          <span class="link-btn" text>Accueil</span>
        </router-link>
        <router-link to="/Store"  class="betweenLinks router">
          <v-icon icon=mdi-gamepad />
          <span class="link-btn" text>Boutique</span>
        </router-link>
        <router-link to="/About"  class="betweenLinks router">
          <v-icon icon="mdi-information" />
          <span class="link-btn" >A propos</span>
        </router-link>

        <v-spacer></v-spacer>
        <router-link to="/Login" class="router" v-if="!isConnected.value">
          <v-icon icon="mdi-login" />
					<span class="link-btn" text>Connexion</span>
        </router-link>
        <router-link to="/Login" class="router" v-else>
          <v-icon icon="mdi-account-circle" class="conectedIcon"/>
        </router-link>

      </div>
    </v-app-bar>
</template>


<script setup>
  import { ref, defineProps } from 'vue';
  import logoURL from '/src/assets/logo golden transparent.png'


    const props = defineProps(['isConnected']);
    console.log(props.isConnected);

  // Récupérer la valeur depuis le stockage local
  /*const storedIsConnected = localStorage.getItem('isConnected');

  // Utiliser la valeur du stockage local ou attribuer false par défaut
  var isConnected = ref(storedIsConnected !== null ? storedIsConnected : false);
  //isConnected.value = true
  console.log("nav " + isConnected.value);
  localStorage.setItem("isConnected", isConnected.value.toString());
  //var isConnected = ref(localStorage.getItem("isConnected") === "false")*/
  
</script>

<!--<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> -->
<style scoped>


.router-link-exact-active{
    /*background-color: rgba(198, 198, 198, 0.112);*/
    border-bottom: 4px solid rgba(163, 163, 163, 0.634);
    font-weight: bolder;
}
  .logo {
    width: 10%;
    text-align: center;
    margin-left: 2%;
  }
  .logo > img {
    width: 75%;
  }
  .link {
    width: 100%;
    height: 100%;
    padding: 0%;
    display: flex;
    align-items: center;
    gap: 1%;
    margin: 0%;
  }
  .link .link-btn {
    margin: auto;
    color: black;
  }
  .link .router {
    flex: 1;
    margin: 0%;
    text-align: center;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
    border-radius: 3px;
    text-decoration: none;
    color: black;
    font-weight: bold;
  }
  .link .router:hover {
    background-color: rgba(128, 128, 128, 0.306);
    border-bottom: 4px solid rgba(128, 128, 128, 0.634);
    font-weight: bolder;
  }
  .link .router .conectedIcon {
    font-size: 3rem;
  }
</style>
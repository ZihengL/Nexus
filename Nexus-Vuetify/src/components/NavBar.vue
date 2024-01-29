<template>
    <v-app-bar :elevation="21" rounded>
      <div class="logo">
				<img :src="logoURL" alt="Vue" />
			</div>
      <div class="link">
        <v-spacer></v-spacer>
        <router-link to="/"  class="betweenLinks">
          <v-btn class="link-btn" text>Accueil</v-btn>
        </router-link>
        <router-link to="/Store"  class="betweenLinks">
          <v-btn class="link-btn" text>Boutique</v-btn>
        </router-link>
        <router-link to="/About"  class="betweenLinks">
          <v-btn class="link-btn" text>A propos</v-btn>
        </router-link>

        <v-spacer></v-spacer>
        <router-link to="/Login">
          <v-btn class="link-btn" text>Connexion</v-btn>
        </router-link>
      </div>
    </v-app-bar>
</template>

<script setup>
  import logoURL from '/src/assets/logo golden transparent.png'
</script>
<style scoped>
  .logo {
    width: 10%;
    text-align: center;
  }
  .logo > img {
    width: 75%;
  }
  .link {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .link-btn {
    color: black;
  }
  .link .betweenLinks {
    flex: 1;
  }
</style>
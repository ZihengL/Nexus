<template>
    <v-app-bar :elevation="21" rounded>
      <div class="logo">
				<img :src="logoURL" alt="Vue" />
			</div>
      <div class="link">
        <v-spacer></v-spacer>
        <v-btn class="betweenLinks" text>Accueil</v-btn>
        <v-btn class="betweenLinks" text>Boutique</v-btn>
        <v-btn class="betweenLinks" text>A propos</v-btn>
        <v-spacer></v-spacer>
        <v-btn text>Connexion</v-btn>
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
  .link .betweenLinks {
    flex: 1;
  }
</style>
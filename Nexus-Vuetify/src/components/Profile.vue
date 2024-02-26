<template>
  <!-- Created By CodingNepal -->
  <div v-if="leDevs && gameList && toggleLogin && toggleSignup" class="allP">
    <div class="containerProfile" :class="isHimself ? 'container1' : 'container2'">
      <div class="wrapper2">
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
        <div class="wrapper glass roundBorderSmall">

          <div class="form-container">

            <div class="slide-controls roundBorderSmall">
              <input type="radio" name="slide" id="login" v-model="isLogin" value="true" checked>
              <input type="radio" name="slide" id="signup" v-model="isLogin" value="false">
              <label for="login" class="slide login">Connexion</label>
              <label for="signup" class="slide signup">Inscription</label>
              <div class="slider-tab"></div>
            </div>

            <div class="form-inner">

              <form action="#" class="login">
                <!-- ... Login form content ... -->
                <div class="field">
                  <input type="text" placeholder="Addresse email" required>
                </div>
                <div class="field">
                  <input type="password" placeholder="Mot de passe" required>
                </div>
                <div class="pass-link glow">
                  <a href="#">Mot de passe oublier ?</a>
                </div>
                <div class="fieldBtn">
                  <div class="btn-layer"></div>
                  <v-btn density="default" class="submit glow" @click="toggleProfile">
                    Se connecter
                  </v-btn>
                </div>
                <div class="signup-link">
                  Pas encore inscris ? <a style=" cursor: pointer;" class=" glow">S'inscrire</a>
                </div>
              </form>

              <form action="#" class="signup">
                <!-- ... Signup form content ... -->
                <div class="field field2">
                  <input type="text" placeholder="Nom *"  required>
                  <input type="text" placeholder="Prenom *"  required>
                </div>
                <div class="field">
                  <input type="text" placeholder="Téléphone">
                </div>
                <div class="field">
                  <input type="text" placeholder="Username *" required>
                </div>
                <div class="field">
                  <input type="text" placeholder="Email *" required>
                </div>
                <div class="field">
                  <input type="password" placeholder="Mot de passe *" required>
                </div>
                <div class="field">
                  <input type="password" placeholder="Confirmer le mot de passe *" required>
                </div>
                <div class="fieldBtn">
                  <div class="btn-layer"></div>
                  <v-btn density="default" class="submit glow" @click="toggleProfile">
                    S'inscrire
                  </v-btn>
                </div>
              </form>

            </div>

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
import loginScript from '../JS/LoginScript.js';
import ListeDeJeu from './ListeDeJeu.vue';
import { fetchData } from '../JS/fetch';
import { defineProps, ref, onMounted } from 'vue';
import Amis from './amis.vue';

const props = defineProps(['isHimself', 'idDevl']);
const leDevs = ref(null);
const gameList = ref(null);
const isLogin = ref(true);

const toggleLogin = () => {
  isLogin.value = true;
};

const toggleSignup = () => {
  isLogin.value = false;
};

onMounted(async () => {

  try {
    loginScript.init({ toggleLogin, toggleSignup });
    const dataGame = await fetchData("users", "getOne", "id", props.idDevl, null, "GET");
    leDevs.value = dataGame;
    console.log('leDevs : ', leDevs)

    if(leDevs.value) {
      const filters = {
        developerID: props.idDevl,
      }
      const sorting = {
        id: false
      }

      const includedColumns = ['id', 'title']
      const jsonBody = { filters, sorting, includedColumns }

      const dataDevs = await fetchData('games', 'getAllMatching', null, null, jsonBody, 'POST');
      gameList.value = dataDevs;
      console.log('game ', gameList)
    }
  } catch (error) {
    console.error('Error fetching data:', error);
  }
});
</script>


<style src="../styles/ProfileStyle.scss"></style>
<style src="../styles/SignRegisterStyle.scss" scoped></style>

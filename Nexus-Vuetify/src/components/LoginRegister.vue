<template>
  <!-- Created By CodingNepal -->
  <div class="wrapper">
    <div class="title-text">
      <div class="title login">
        Se connecter
      </div>
      <div class="title signup">
        S'inscrire
      </div>
    </div>

    <div class="form-container">

      <div class="slide-controls">
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
          <div class="pass-link">
            <a href="#">Mot de passe oublier ?</a>
          </div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" @click="toggleProfile">
              Se connecter
            </v-btn>
          </div>
          <div class="signup-link">
            Pas encore inscris ? <a style=" cursor: pointer;">S'inscrire</a>
          </div>
        </form>

        <form action="#" class="signup">
          <!-- ... Signup form content ... -->
          <div class="field">
            <input type="text" placeholder="Nom">
          </div>
          <div class="field">
            <input type="text" placeholder="Prenom">
          </div>
          <div class="field">
            <input type="text" placeholder="Téléphone" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Username" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Email" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Mot de passe" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Confirmer le mot de passe" required>
          </div>
          <div class="field btn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit" @click="toggleProfile">
              S'inscrire
            </v-btn>
          </div>
        </form>

      </div>

    </div>

  </div>
</template>

<script>
import loginScript from '../JS/LoginScript.js';

export default {
  data() {
    return {
      isLogin: true,
    };
  },
  mounted() {
    loginScript.init(this);
  },
  methods: {
    toggleLogin() {
      this.isLogin = true;
    },
    toggleSignup() {
      this.isLogin = false;
    },
    toggleProfile() {
      this.$emit('showProfile')
    },
  },
};
</script>

<style src="../styles/SignRegisterStyle.scss" scoped></style>

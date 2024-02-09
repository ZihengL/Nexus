<template>
  <!-- Created By CodingNepal -->
  <div class="wrapper glass">

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
      console.log("c est true");
      var formInner =  document.querySelector(".form-inner");
      formInner.style.display = 'block';
      formInner.style.height = '35svh';
    },
    toggleSignup() {
      this.isLogin = false;
      console.log("c est false");
      var formInner =  document.querySelector(".form-inner");
      formInner.style.display = 'flex';
      formInner.style.flexDirection = 'row';
      formInner.style.height = '75svh';
    },
    toggleProfile() {
      this.$emit('showProfile');
    },
  },
};
</script>

<style src="../styles/SignRegisterStyle.scss" scoped></style>

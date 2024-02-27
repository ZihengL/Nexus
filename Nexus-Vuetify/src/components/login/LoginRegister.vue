<template>
  <!-- Created By CodingNepal -->
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

        <form action="#" class="login log">
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

        <form action="#" class="signup sign">
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
</template>

<script setup>
import loginScript from '../../JS/LoginScript.js';
import { ref, onMounted, defineEmits } from 'vue';

const isLogin = ref(true);
const emit = defineEmits(['showProfile']);

const toggleLogin = () => {
  isLogin.value = true;
  console.log("c'est true");
  const formInner = document.querySelector(".form-inner");
  formInner.style.height = '45svh';
};

const toggleSignup = () => {
  isLogin.value = false;
  console.log("c'est false");
  const formInner = document.querySelector(".form-inner");
  formInner.style.height = '80svh';
};

const toggleProfile = () => {
  emit('showProfile');
};

onMounted(() => {
  loginScript.init({ toggleLogin, toggleSignup });
});
</script>


<style src="../../styles/SignRegisterStyle.scss" scoped></style>

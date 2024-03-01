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

      <div class="form-inner ">

        <form action="#" class="login log">
          <!-- ... Login form content ... -->
          <div class="field">
            <input type="text" v-model="email" placeholder="Addresse email" required>
          </div>
          <div class="field">
            <input type="password" v-model="password" placeholder="Mot de passe" required>
          </div>
          <div class="pass-link glow">
            <a href="#">Mot de passe oublier ?</a>
          </div>
          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit glow" @click="toggleProfileLog">
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
            <v-btn density="default" class="submit glow" @click="toggleProfileSign">
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
import { loginService } from '../../JS/fetchServices';
import { fetchData } from '../../JS/fetch';

const isLogin = ref(true);
const emit = defineEmits(['showProfile']);
let loginTokens_access_token;
let loginTokens_refresh_token;
let idDev = null;
let email= ref(null);
let password = ref(null);

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

const toggleProfileLog = async () => {
  const login = {
    email: email.value,
    password: password.value,
  };
  //console.log("var : ", email, " var : ", password);
  //const login = { login };
  try {
    const loginResponse =  await loginService(login)
    console.log("Login successful : ", loginResponse);
    if (loginResponse) {
      loginTokens_access_token = loginResponse.access_token;
      loginTokens_refresh_token = loginResponse.refresh_token;
      localStorage.setItem("accessToken", loginResponse.access_token);
      localStorage.setItem("refreshToken", loginResponse.refresh_token);

      // const filters = {
      //   email: email.value,
      // }
      // const sorting = {
      //   id: false
      // }
      // const includedColumns = ['id']
      // const jsonBody = { filters, sorting, includedColumns }

      // const devId = await fetchData('users', 'getAllMatching', null, null, null, null, jsonBody, 'POST');
      // console.log('devs : ', devId[0].id)
      // idDev = devId[0].id;
      // localStorage.setItem("idDev", devId[0].id);

      // console.log("id : ", idDev);
      //emit('showProfile');
    }

  }
  catch (error) {
    console.error("Login failed: ", error);
  }
};
const toggleProfileSign = () => {
  const createData = {
    email: "c",
    username: "meki",
    password: "c",
  };
  const createBody = { createData };
  let results = fetchData(
    "users",
    "create",
    null,
    null,
    null,
    null,
    createBody,
    "POST"
  );
  console.log(results);
  //emit('showProfile');
};

onMounted(() => {
  loginScript.init({ toggleLogin, toggleSignup });
});
</script>


<style src="../../styles/SignRegisterStyle.scss" scoped></style>

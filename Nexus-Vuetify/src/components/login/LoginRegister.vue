<template>
  <!-- Created By CodingNepal -->
  <div class="wrapper glass roundBorderSmall">
    <div class="form-container">
      <div class="slide-controls roundBorderSmall">
        <input
          type="radio"
          name="slide"
          id="login"
          v-model="isLogin"
          value="true"
          checked
        />
        <input
          type="radio"
          name="slide"
          id="signup"
          v-model="isLogin"
          value="false"
        />
        <label for="login" class="slide login">Connexion</label>
        <label for="signup" class="slide signup">Inscription</label>
        <div class="slider-tab"></div>
      </div>

      <div class="form-inner">
        <form action="#" class="login log">
          <!-- ... Login form content ... -->
          <div class="field">
            <input
              type="text"
              v-model="email"
              placeholder="Addresse email"
              required
            />
          </div>
          <div class="field">
            <input
              type="password"
              v-model="password"
              placeholder="Mot de passe"
              required
            />
          </div>
          <div class="pass-link glow">
           <!-- <a href="#">Mot de passe oublier ?</a>-->
          </div>
          <btnComp :contenu="'Se connecter'" @toggle-btn="toggleProfileLog()" />
          <div class="signup-link">
            Pas encore inscris ?
            <a style="cursor: pointer" class="glow">S'inscrire</a>
          </div>
        </form>

        <form action="#" class="signup sign">
          <!-- ... Signup form content ... -->
          <div class="field field2">
            <input
              type="text"
              v-model="lastnameSign"
              placeholder="Nom "
              required
            />
            <input
              type="text"
              v-model="firstnameSign"
              placeholder="Prenom "
              required
            />
          </div>
          <div class="field">
            <input type="text" v-model="telSign" placeholder="Téléphone" />
          </div>
          <div class="field">
            <input
              type="text"
              v-model="usernameSign"
              placeholder="Username *"
              required
            />
          </div>
          <div class="field">
            <input type="text" v-model="email" placeholder="Email *" required />
          </div>
          <div class="field">
            <input
              type="password"
              v-model="password"
              placeholder="Mot de passe *"
              required
            />
          </div>
          <div class="field">
            <input
              type="password"
              v-model="passwordConfSign"
              placeholder="Confirmer le mot de passe *"
              required
            />
            
          </div>
          <btnComp :contenu="'S\'inscrire'" @toggle-btn="toggleProfileSign()" />
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import loginScript from "../../JS/LoginScript.js";
import storageManager from "../../JS/localStorageManager.js"
import { ref, onMounted, defineEmits } from "vue";
import { loginService, registerService, getOne } from "../../JS/fetchServices";
import { fetchData } from "../../JS/fetch";
import btnComp from "../btnComponent.vue";

const isLogin = ref(true);
const emit = defineEmits(["showProfile"]);
let loginTokens_access_token;
let loginTokens_refresh_token;
let idDev = null;

let email = ref(null);
let password = ref(null);

let lastnameSign = ref(null);
let firstnameSign = ref(null);
let telSign = ref(null);
let usernameSign = ref(null);
let passwordConfSign = ref(null);

const toggleLogin = () => {
  isLogin.value = true;
  //console.log("c'est true");
  const formInner = document.querySelector(".form-inner");
  formInner.style.height = "45svh";
};

const toggleSignup = () => {
  isLogin.value = false;
  //console.log("c'est false");
  const formInner = document.querySelector(".form-inner");
  formInner.style.height = "80svh";
};

const toggleProfileLog = async () => {
  const login = {
    email: email.value,
    password: password.value,
  };
  console.log("var : ", email.value, " var : ", password.value);
  //const login = { login };
  try {
    
    const loginResponse = await loginService(login);
   // const devId = await getOne("users", "email", email.value, ["id"])
    //console.log('loginRegister devId : ', loginResponse)

    //console.log("Login successful : ", loginResponse);
    if (loginResponse !== false) {
      //console.log("loginResponse : ", loginResponse);
      loginTokens_access_token = loginResponse.access_token;
      loginTokens_refresh_token = loginResponse.refresh_token;
      storageManager.setAccessToken(loginResponse.access_token)
      // localStorage.setItem("accessToken", loginResponse.access_token);
      storageManager.setRefreshToken(loginResponse.refresh_token)
      // localStorage.setItem("refreshToken", loginResponse.refresh_token);

      // localStorage.setItem("idDev", devId);
      storageManager.setIdDev(loginResponse.id)

      console.log("devId : ", loginResponse.id);
      emit("showProfile");
    }
  } catch (error) {
    console.error("Login failed: ", error);
  }
};


const validateSignupData = () => {
  let errors = [];

  // Check for empty required fields
  if (!email.value) errors.push("Email is required.");
  if (!usernameSign.value) errors.push("Username is required.");
  if (!password.value) errors.push("Password is required.");
  if (!passwordConfSign.value) errors.push("Password confirmation is required.");

  if (password.value !== passwordConfSign.value) errors.push("Passwords do not match.");

  if (errors.length > 0) {
    console.error("Validation errors:", errors.join("\n"));
    return false;
  }
  return true;
};

const toggleProfileSign = async () => {
  if (validateSignupData()) {
    const createData = {
      email: email.value,
      username: usernameSign.value,
      password: password.value,
      lastname: lastnameSign.value ? lastnameSign.value : null,
      firstname: firstnameSign.value ? firstnameSign.value : null,
      tel: telSign.value ? telSign.value : null,
    };

    console.log("createData:", createData);

    try {
      let isRegistered = await registerService(createData);
      console.log("isRegistered :", isRegistered);
      if(isRegistered){
        // toggleProfileLog()
      }
      toggleProfileLog();
    } catch (error) {
      console.error("Sign up failed:", error);
      // Optionally, display this error to the user using the UI
    }
  } else {
    console.error("Validation failed, sign up not processed.");
  }
};

onMounted(() => {
  // console.log("onMounted  storageManager.getIdDev() : ", storageManager.getIdDev());
  if (storageManager.getIdDev()) {
    emit("showProfile")
  }
  loginScript.init({ toggleLogin, toggleSignup });
});
</script>

<style src="../../styles/SignRegisterStyle.scss" scoped></style>

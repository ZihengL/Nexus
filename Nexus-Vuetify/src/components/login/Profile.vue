<template>
  <!-- Created By CodingNepal -->
  <div v-if="isLoading" class="loading-state">
    Loading...
  </div>
  <div v-else-if="leDevs && gameList && toggleLogin && toggleSignup" class="allP">
    <div
      class="containerProfile"
      :class="isHimself ? 'container1' : 'container2'"
    >
      <div class="wrapper2">
        <div class="description glass roundBorderSmall">
          <div :class="isHimself ? 'imgContainerFull' : 'imgContainer'">
            <img
              :src="leDevs.picture || defaultProfilePic"
              alt="Photo de Profile"
              class="imgProfil"
            />
          </div>
          <div class="champUtilisateur">
            <!-- <h3>efew4w</h3> -->
            <h3>{{ leDevs.username }}</h3>
            <br />
            <p>{{ leDevs.description || 'Aucune Description...' }}</p>
          </div>
          <div class="button" v-show="isHimself">
            <router-link
              :to="{ name: 'Profile', params: { IdDev: props.idDevl } }"
              class="router glow"
            >
              <v-icon icon="mdi-account-circle" />
              <span class="link-btn">Gérer son profil</span>
            </router-link>

            <btnComp :contenu="'Se déconnecter'" @toggle-btn="toggleLogout" />
          </div>
        </div>
        <div class="wrapper glass roundBorderSmall">
          <div class="form-container">
            <div v-if="isHimself" class="slide-controls roundBorderSmall">
              <input type="radio" name="slide" id="login" v-model="isLogin" value="true" checked />
              <input type="radio" name="slide" id="signup" v-model="isLogin" value="false" />
              <label for="login" class="slide login" @click="toggleLogin()">Développer</label>
              <label for="signup" class="slide signup" @click="toggleSignup()">Télécharger</label>
              <div class="slider-tab"></div>
            </div>

            <div v-if="isHimself" class="form-inner">
              <div class="login log gamesss">
                <div
                  v-for="(item, index) in gameList"
                  :key="index"
                  class="gamess">
                  <liste-de-jeu
                    :himself="props.isHimself"
                    :idJeu="item.id"
                    :buy="true"
                    class="game gamess"
                  />
                </div>
              </div>

              <div class=" sign glass roundBorderSmall">
                <p>Comming soon ...</p>
              </div>
            </div>

            <div v-else class="signup">
              <div
                v-for="(item, index) in gameList"
                :key="index"
              >
                <liste-de-jeu
                  :himself="props.isHimself"
                  :idJeu="item.id"
                  :buy="false"
                  class="game"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <router-link
      v-if="isHimself"
      class="floating-right-bottom-btn glass"
      to="/upload"
      title="upload"
    >
      <v-icon icon="mdi-upload" class="icon glow" />
    </router-link>
  </div>
  <div v-else-if="leDevs == null" >
    <p class="errorMsg">{{ errorMsg }}</p>
    <btnComp :contenu="'Se déconnecter'" @toggle-btn="toggleLogout" style="align-self: center" />
  </div>
</template>

<script setup>
import storageManager from "../../JS/localStorageManager";
import ListeDeJeu from "./ListeDeJeu.vue";
import { logoutService, getOne, getAllMatching } from "../../JS/fetchServices";
import { defineProps, ref, onMounted, defineEmits } from "vue";
import btnComp from "../btnComponent.vue";
import defaultProfilePic from '@/assets/Dev_Picture/defaultProfilePic.png';
//import Amis from './amis.vue';

const props = defineProps(["isHimself", "idDevl"]);
const emit = defineEmits(["showProfile"]);
let devId = props.idDevl;

const leDevs = ref(null);
const errorMsg = ref("Unsuccessful login");
const gameList = ref(null);
const isLogin = ref(true);
const isLoading = ref(true);

let loginTokens_access_token;
let loginTokens_refresh_token;

const toggleLogin = () => {
  isLogin.value = true;
  const gamessShow = document.querySelector(".gamess"); // Use class selector
  const gamesContainer = document.querySelector(".gamesss"); // Use class selector
  if (gamessShow && gamesContainer) {
    gamessShow.style.marginLeft = "0%";
    gamesContainer.style.marginLeft = "0%";
  }
};

const toggleSignup = () => {
  isLogin.value = false;
  const gamessShow = document.querySelector(".gamess"); // Use class selector
  const gamesContainer = document.querySelector(".gamesss"); // Use class selector
  if (gamessShow && gamesContainer) {
    gamessShow.style.marginLeft = "-60%";
    gamesContainer.style.marginLeft = "-60%";
  }
};

const toggleLogout = async () => {
  // loginTokens_access_token = storageManager.getAccessToken();
  // console.log("loginTokens_access_token  : ", loginTokens_access_token);
  // localStorage.getItem("accessToken");
  // loginTokens_refresh_token = storageManager.getRefreshToken();
  // console.log("loginTokens_refresh_token : ", loginTokens_refresh_token);
  // localStorage.getItem("refreshToken");

  const logout = {
    id: props.idDevl,
    tokens: {
      access_token: loginTokens_access_token,
      refresh_token: loginTokens_refresh_token,
    },
  };

  let results = await logoutService(logout);
  if (results !== false) {
    loginTokens_access_token = "";
    loginTokens_refresh_token = "";

    // localStorage.removeItem("accessToken");
    storageManager.clearAccessToken();
    // localStorage.removeItem("refreshToken");
    storageManager.clearRefreshToken();
    // localStorage.removeItem("idDev");
    storageManager.clearIdDev();
  }
  console.log(results);
  emit("showLogin");
};

async function getUserInfos() {
  try {
    // console.log("Profile.vue props.idDevl : ", props.idDevl);
    if (props.idDevl) {
      const userData = await getOne("users", "id", props.idDevl);

      console.log("userData : ", userData);

      leDevs.value = userData;
      console.log("leDevs : ", leDevs.value);

      if (leDevs.value) {
        
        storageManager.setIsConnected(true);
        const filters = {
          developerID: props.idDevl,
        };
        const sorting = {
          id: false,
        };
        const includedColumns = ["id", "title", "tags"];
        const dataDevs = await getAllMatching(
          "games",
          filters,
          includedColumns,
          sorting
        );
        // console.log("dataDevs ", dataDevs);
        gameList.value = dataDevs;
        // console.log("gameList ", gameList);
      }
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

onMounted(async () => {
  try {
    await getUserInfos();
    loginTokens_access_token = storageManager.getAccessToken();
    // console.log("loginTokens_access_token  : ", loginTokens_access_token);
    // localStorage.getItem("accessToken");
    loginTokens_refresh_token = storageManager.getRefreshToken();
    // console.log("loginTokens_refresh_token : ", loginTokens_refresh_token);
    // localStorage.getItem("refreshToken");
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    isLoading.value = false;
  }
});


</script>

<style src="../../styles/ProfileStyle.scss"></style>
<style src="../../styles/SignRegisterStyle.scss" scoped></style>

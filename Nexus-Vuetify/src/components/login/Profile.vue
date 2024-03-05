<template>
  <!-- Created By CodingNepal -->
  <div v-if="leDevs && gameList && toggleLogin && toggleSignup" class="allP">
    <div
      class="containerProfile"
      :class="isHimself ? 'container1' : 'container2'"
    >
      <div class="wrapper2">
        <div class="description glass roundBorderSmall">
          <div :class="isHimself ? 'imgContainerFull' : 'imgContainer'">
            <img
              src="../../assets/Rich_Ricasso.png"
              alt="John"
              class="imgProfil"
            />
          </div>
          <div class="champUtilisateur">
            <h3>efew4w</h3>
            <!--<h3>{{ leDevs.value.username }}</h3>-->
            <br />
            <p>description</p>
          </div>
          <div class="button" v-show="isHimself">
            <router-link
              :to="{ name: 'Profile', params: { IdDev: props.idDevl } }"
              class="router glow"
            >
              <v-icon icon="mdi-account-circle" />
              <span class="link-btn">Gerer son profil</span>
            </router-link>

            <btnComp :contenu="'Se deconnecter'" @toggle-btn="toggleLogout"/>

          </div>
        </div>
        <div class="wrapper glass roundBorderSmall">
          <div class="form-container">
            <div v-if="isHimself" class="slide-controls roundBorderSmall">
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
              <label for="login" class="slide login" @click="toggleLogin()"
                >Acheter</label
              >
              <label for="signup" class="slide signup" @click="toggleSignup()"
                >Developper</label
              >
              <div class="slider-tab"></div>
            </div>

            <div v-if="isHimself" class="form-inner">
              <div
                v-for="(item, index) in gameList"
                :key="index"
                class="login gamesss log"
              >
                <liste-de-jeu
                  :himself="props.isHimself"
                  :idJeu="item.id"
                  :buy="true"
                  class="game gamess"
                />
              </div>

              <div
                v-for="(item, index) in gameList"
                :key="index"
                class="signup sign"
              >
                <liste-de-jeu
                  :himself="props.isHimself"
                  :idJeu="item.id"
                  :buy="false"
                  class="game"
                />
              </div>
            </div>
            <div
              v-else
              v-for="(item, index) in gameList"
              :key="index"
              class="signup sign"
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

    <router-link
      class="floating-right-bottom-btn glass"
      to="/upload"
      title="upload"
    >
      <v-icon icon="mdi-upload" class="icon glow" />
    </router-link>
  </div>
</template>

<script setup>
import ListeDeJeu from "./ListeDeJeu.vue";
import { logoutService, getOne, getAllMatching } from "../../JS/fetchServices";
import { defineProps, ref, onMounted, defineEmits } from "vue";
import btnComp from "../btnComponent.vue"
//import Amis from './amis.vue';

const props = defineProps(["isHimself", "idDevl"]);
const emit = defineEmits(["showProfile"]);
let devId = props.idDevl;

const leDevs = ref(null);
const gameList = ref(null);
const isLogin = ref(true);

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
  loginTokens_access_token = localStorage.getItem("accessToken");
  loginTokens_refresh_token = localStorage.getItem("refreshToken");

  const logout = {
    id: devId,
    tokens: {
      access_token: loginTokens_access_token,
      refresh_token: loginTokens_refresh_token,
    },
  };

  loginTokens_access_token = "";
  loginTokens_refresh_token = "";

  localStorage.removeItem("accessToken");
  localStorage.removeItem("refreshToken");
  localStorage.removeItem("idDev");

  const body = { logout };

  let results = await logoutService(body);
  console.log(results);
  emit("showLogin");
};

async function getUserInfos() {
  try {
    const userData = await getOne("users", "id", devId);
    console.log("userData : ", userData);

    leDevs.value = userData;
    console.log("leDevs : ", leDevs.value);

    if (leDevs.value) {
      const filters = {
        developerID: devId,
      };
      const sorting = {
        id: false,
      };
      const includedColumns = ["id", "title"];
      const dataDevs = await getAllMatching(
        "games",
        filters,
        sorting,
        includedColumns
      );
      console.log("dataDevs ", dataDevs);
      gameList.value = dataDevs;
      console.log("gameList ", gameList);
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

onMounted(async () => {
  try {
    await getUserInfos();
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});
</script>

<style src="../../styles/ProfileStyle.scss"></style>
<style src="../../styles/SignRegisterStyle.scss" scoped></style>

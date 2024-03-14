<template>
  <div class="loginProfile">
    <LoginRegister
      v-if="!props.connectedView"
      @showProfile="toggleProfileForm()"
    />
    <Profile
      v-else-if="idDev"
      :key="idDev.value"
      @showLogin="toggleLoginForm"
      :isHimself="true"
      :idDevl="idDev"
    />
  </div>
</template>

<script setup>
import LoginRegister from "../components/login/LoginRegister.vue";
import storageManager from "../JS/localStorageManager.js";
import Profile from "../components/login/Profile.vue";
import { defineProps, ref, watch, onMounted } from "vue";

const props = defineProps({
  connectedView: {
    type: Boolean,
    default: false,
  },
  // idDev: {
  //   type: Number,
  //  }
});

let idDev = ref(null);
const emit = defineEmits(["changeCon","showProfile"]);

onMounted(() => {
  // console.log("onMounted  props.connectedView : ", props.connectedView);
  // console.log("onMounted  storageManager.getIdDev() : ", storageManager.getIdDev());
  // console.log("storageManager.getIsConnected() : ", storageManager.getIsConnected());
  if (storageManager.getIdDev()) {
    idDev.value = storageManager.getIdDev()
    // console.log("onMounted  storageManager.getIdDev() : ", storageManager.getIdDev());
    emit("showProfile")
  }
});

watch(
  () => props.connectedView,
  (newValue) => {
    console.log("watch props.connectedView : ", newValue);
  }
);


const changeConnexion = () => {
  // console.log("login-Profile changeConnexion idDev.value : ", idDev.value);
  if (idDev.value) {
    emit("changeCon");
    // Save isConnected state to localStorage
    //localStorage.setItem('isConnected', isConnected.value);
  }
};

const toggleProfileForm = (devId) => {
  if (devId && devId.id) {
    storageManager.clearIdDev()
    idDev.value = devId.id;
    storageManager.setIdDev(idDev.value);
    // console.log("login-Profile toggleProfileForm idDev.value : ", idDev.value);
    // console.log("login-Profile toggleProfileForm storageManager.getIdDev() : ", storageManager.getIdDev());
    changeConnexion();
  } 
};

const toggleLoginForm = () => {
  //isConnected.value = !isConnected.value;
  changeConnexion();
};
</script>

<style lang="scss" scoped>
.loginProfile {
  text-align: center;
  padding: 1% 0% 2% 0%;
}
</style>

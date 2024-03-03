<template>
  <div class="loginProfile">
    <LoginRegister v-if="!isConnected" @showProfile="toggleProfileForm" />
    <Profile
      v-else
      @showLogin="toggleLoginForm"
      :isHimself="true"
      :idDevl="devId"
    />
  </div>
</template>

<script setup>
import LoginRegister from "../components/login/LoginRegister.vue";
import Profile from "../components/login/Profile.vue";
import { defineProps, ref, watch, onMounted } from "vue";

/*const props = defineProps({
  connectedView: {
    type: Boolean,
    default: false,
  },
  idDev: {
    type: Number,
    default: 0, 
  },
});*/


let devId;
const props = defineProps(['connectedView', 'idDev']); // Supprimez le `: devId`


// Accessing `localStorage` within the setup function to set `idDev` if not provided
//const idDev = ref(props.idDev || Number(localStorage.getItem("idDev") || 0));
let isConnected = ref(false);

isConnected.value = props.connectedView;

const emit = defineEmits(["changeCon"]);
const changeConnexion = () => {
  emit("changeCon");
};

//const devsId = 3;

watch(
  () => props.connectedView,
  (newValue, oldValue) => {
    isConnected.value = props.connectedView;
    //console.log("var update : ", newValue);
  }
);

onMounted(async () => {
  if (props.idDev == null){
    devId = localStorage.getItem("idDev");
  }
  else{
    devId = props.idDev;
  }

});


const toggleProfileForm = () => {
  changeConnexion();
};

const toggleLoginForm = () => {
  changeConnexion();
};
</script>

<style lang="scss" scoped>
.loginProfile {
  text-align: center;
  padding: 1% 0% 2% 0%;
}
</style>

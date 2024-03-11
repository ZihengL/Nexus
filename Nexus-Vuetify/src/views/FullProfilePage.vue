<template>
  <div v-if="user" id="fullProfile">
    <form action="#" class="glass">
      <v-avatar size="10rem">
        <img :src="user.picture || defaultPic" alt="Profile Picture" class="img" />
      </v-avatar>
      <!-- ... Signup form content ... -->
      <div class="field field2">
        <input type="text" :placeholder="user.user" required />
        <input type="text" placeholder="Prenom *" />
      </div>
      <div class="field">
        <input type="text" placeholder="Téléphone" />
      </div>
      <div class="field">
        <input type="text" placeholder="Username" required />
      </div>
      <div class="field">
        <input type="text" placeholder="Email" required />
      </div>
      <div class="field">
        <input type="password" placeholder="Mot de passe" required />
      </div>
      <div class="field">
        <input
          type="password"
          placeholder="Confirmer le mot de passe"
          required
        />
      </div>
      <btnComp :contenu="'Modifier'" @toggle-btn="updateUserInfos" />
    </form>
  </div>
</template>

<script setup>
import btnComp from "../components/btnComponent.vue";
import defaultProfilePic from '../assets/Dev_Picture/defaultProfilePic.png';
import { defineProps, ref, onMounted } from "vue";
import { fetchData } from "../JS/fetch";
import { create, getOne } from "../JS/fetchServices";

const props = defineProps(["IdDev"]);
let user = ref(null);
const defaultPic = ref(defaultProfilePic);
const username = ref(null);
const email = ref(null);
const bio = ref(null);
const isUsernameValid = true;
const isEmailValid = true;
const isBioValid = true;

const getUserInfos = async () => {
  const dataUser = await getOne("users", "id", props.IdDev)
  console.error("dataUser :", dataUser);
  user.value = dataUser;
  console.error(" user.value :",  user.value);
}


const updateUserInfos = async () => {
  console.error(" updated User :");
}

onMounted(async () => {
  try {
    await getUserInfos()
  //   const dataUser = await fetchData(
  //     "users",
  //     "getOne",
  //     "id",
  //     props.IdDev,
  //     null,
  //     null,
  //     null,
  //     "GET"
  //   );
  //   //  await getOne("users", "id", props.IdDev)
  //   user.value = dataUser;
  //   //console.log('LeGame : ' , LeGame._rawValue.developerID)
  } catch (error) {
    console.error("Error fetching data:", error);
  }
});

/*const  saveProfile = () => {
  this.isUsernameValid = !!this.username;
  this.isEmailValid = !!this.email;
  this.isBioValid = !!this.bio;
  
  if (this.isUsernameValid && this.isEmailValid && this.isBioValid) {
    alert('Profile saved successfully!');
    // Implement the save logic here
  }
};*/
</script>



<style lang="scss" scoped>
#fullProfile {
  width: 100%;
  margin: 0 auto 3% auto;
  /*max-height: 90%;
  margin: 20px auto;
  background-color: #ffffff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  height: 95svh;*/

  form {
    width: 50%;
    padding: 2% 2%;
    margin: auto;

    .field2 {
      display: flex;
      flex-direction: row;
      gap: 3%;
    }
  }
}
</style>



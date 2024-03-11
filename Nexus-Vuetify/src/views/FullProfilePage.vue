<template>
  <div v-if="user != null" :key="user.value" id="fullProfile">
    <form action="#" class="glass">
      <v-avatar size="10rem">
        <img :src="defaultPic" alt="Profile Picture" class="img" />
      </v-avatar>
      <!-- ... Signup form content ... -->
      <div class="field field2">
        <span class="title">Prénom</span>
        <input type="text" :placeholder="user.name || 'Prénom'" v-model="state.name" required />
         
        <span class="title">Nom</span>
        <input type="text" :placeholder="user.lastName"  v-model="state.lastname" />
      </div>
      <!-- Phone Number -->
      <div class="field">
        <span class="title">Téléphone</span>
        <input type="text"  :placeholder="user.phoneNumber || 'Téléphone'"  v-model="state.phoneNumber" />
      </div>
      <!-- Username -->
      <div class="field">
        <span class="title">Nom Utilisateur</span>
        <input type="text"  :placeholder="user.username || 'Nom Utilisateur'"  v-model="state.username"  />
      </div>
      <!-- Email -->
      <!-- <div class="field">
        <span class="title">Email</span>
        <input type="text"  :placeholder="user.email || 'Email'" required />
      </div> -->
      <!-- Password -->
      <div class="field">
        <span class="title">Mot de Passe</span>
        <input type="password" placeholder="Mot de passe" v-model="state.firstPassword"  />
      </div>
      <!-- Confirm Password -->
      <div class="field">
        <span class="title">Confirmer Mot De Passe</span>
        <input
          type="password"
          placeholder="Confirmer le mot de passe"
          v-model="state.secondPassword" 
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
import { defineProps, ref, onMounted, watch, reactive } from "vue";
import storageManager from "../JS/localStorageManager.js";
import { fetchData } from "../JS/fetch";
import { updateData, getOne } from "../JS/fetchServices";

const props = defineProps(["IdDev", "user"]);
let user = ref(null);
const defaultPic = ref(defaultProfilePic);
const username = ref(null);
const email = ref(null);
const bio = ref(null);
const isUsernameValid = true;
const isEmailValid = true;
const isBioValid = true;

const state = reactive({
  name:"",
  lastname:"",
  email:"",
  username:"",
  phoneNumber:"",
  firstPassword:"",
  secondPassword:"",
});

const getUserInfos = async () => {
  const dataUser = await getOne("users", "id", storageManager.getIdDev())
  console.log("dataUser :", dataUser);
  user.value = dataUser;
  console.log(" user.value :",  user.value);
}


const validateData = async () => {
  if (!user.value) {
    state.errorMessage = "User does not exist.";
    return false;
  }
  if (!state.username.trim()) {
    state.errorMessage = "username cannot be empty.";
    return false;
  }
  if (state.firstPassword.trim() != state.secondPassword.trim() && (!state.firstPassword.trim() && !state.secondPassword.trim())) {
    state.errorMessage = "Passwords not equal or not given.";
    return false;
  }
  state.errorMessage = "";
  return true;
};


const updateUserInfos = async () => {
  // First, validate the input data
  // const isValid = await validateData();
  // if (!isValid) {
  //   alert(state.errorMessage);
  //   return;
  // }
  const user = await getOne("users", "id", storageManager.getIdDev())
  console.log("updateUserInfos user :" , user)
  if(user){
 // Prepare the payload with only the fields that have been changed
 const updatedUser = {
    ...(state.name && { name: state.name }),
    ...(state.lastname && { lastName: state.lastname }),
    ...(state.phoneNumber && { phoneNumber: state.phoneNumber }),
    ...(state.username && { username: state.username }),
    ...(state.firstPassword && state.secondPassword && { password: state.firstPassword }),
  };

  try {
    // let userIsUpdated = await updateData("users", user.value.id, updatedUser);

    // if (userIsUpdated) {
    //   alert("User information updated successfully.");
    // } else {
    //   alert("Failed to update user information.");
    // }
  } catch (error) {
    console.error("Error updating user information:", error);
  }
  }
 
};


// watch(
//   () => user,
//   (newVal, oldVal) => {
//     console.log("watch user : ", newVal.value)
//   },
//   { deep: true }
// );


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



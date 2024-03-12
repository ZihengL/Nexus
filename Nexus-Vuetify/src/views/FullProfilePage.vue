<template>
  <div v-if="user != null" :key="user.value" id="fullProfile">
    <form action="#" class="glass">
      <v-avatar size="10rem">
        <img :src="user.picture || defaultProfilePic" alt="Profile Picture" class="img" />
      </v-avatar>
      <!-- ... Signup form content ... -->
      <div class="field field2">
        <span class="title">Prénom</span>
        <input :class="{'notEmptyInput': user.name }" type="text" :placeholder="user.name || 'Prénom'" v-model="state.name" required />
         
        <span class="title">Nom</span>
        <input  :class="{'notEmptyInput': user.lastName }" type="text" :placeholder="user.lastName|| 'Nom'"  v-model="state.lastName" />
      </div>
      <!-- Phone Number -->
      <div class="field">
        <span class="title">Téléphone</span>

        <input type="tel"  placeholder="Numero de Téléphone"  v-model="state.phoneNumber" />
      </div>
      <!-- Username -->
      <div class="field">
        <span class="title">Nom Utilisateur</span>
        <input :class="{'notEmptyInput': user.username }" type="text"  :placeholder="user.username || 'Nom Utilisateur'"  v-model="state.username"  />
      </div>
      <!-- Email -->
      <!-- <div class="field">
        <span class="title">Email</span>
        <input type="text"  :placeholder="user.email || 'Email'" required />
      </div> -->
 <!-- Description-->
      <div class="field">
        <span class="description">Description</span>
        <textarea :class="{'notEmptyInput': user.description }" type="text" :placeholder="user.description || 'Description...'" v-model="state.description" />
      </div>
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
import { defineProps, ref, onMounted, watch, reactive } from "vue";
import storageManager from "../JS/localStorageManager.js";
import defaultProfilePic from '@/assets/Dev_Picture/defaultProfilePic.png';
import { fetchData } from "../JS/fetch";
import { updateData, getOne } from "../JS/fetchServices";

const props = defineProps(["IdDev"]);
let user = ref(null);


const state = reactive({
  name:"",
  lastName:"",
  email:"",
  username:"",
  phoneNumber:"",
  firstPassword:"",
  secondPassword:"",
  description:"",
  erroMsg:"",
  MAX_DESC_LENGTH:250,
});

const getUserInfos = async () => {
  try {
    const dataUser = await getOne("users", "id", storageManager.getIdDev());

    if (dataUser) {
      user.value = dataUser;
    }
    console.log(" dataUser : ",  dataUser);
  } catch (error) {
    console.error("Error fetching user data:", error);
  }
};


const updateUserInfos = async () => {
  const originalUser = await getOne("users", "id", storageManager.getIdDev());
  console.log("Original user info:", originalUser.id);
  if (originalUser) {
    // Initialize updatedUser with the user ID
    const updatedUser = {
      id: originalUser.id, // Always include the user ID
    };

    // List of fields to potentially update
    const fields = ['name', 'lastName', 'phoneNumber', 'username', "description"];

    // Check each field for changes and non-null values, then add to updatedUser
    fields.forEach(field => {
      const newValue = state[field];
      if (newValue && newValue !== originalUser[field]) {
        updatedUser[field] = newValue;
      }
    });

    // Handle password validation and updating
    if (state.firstPassword && state.secondPassword) {
      if (state.firstPassword === state.secondPassword) {
        updatedUser.password = state.firstPassword;
      } else {
        console.error("Les mots de passe ne correspondent pas.");
        return; 
      }
    }

    // Handle decription 
    if (state.description && state.description.trim().length > 0 && state.description.trim().length <= state.MAX_DESC_LENGTH) {
        updatedUser.description = state.description;
      } else {
        console.error("Mauvaise longueur de description");
        return; 
      }
    
    const isUpdatedUserEmpty = Object.keys(updatedUser).length <= 1; 
    if (!isUpdatedUserEmpty) {
      try {
        console.log("Updating user information...");
        updatedUser.tokens ={
          access_token : storageManager.getAccessToken(),
          refresh_token : storageManager.getRefreshToken()
        }
        console.log("updatedUser payload:", updatedUser);
        let userIsUpdated = await updateData("users",  updatedUser);
        if(userIsUpdated != false){
          console.log("SUCCESSFULLY UPDATED USER")
          storageManager.setAccessToken(userIsUpdated["access_token"])
          // console.log("storageManager.getAccessToken() : ", storageManager.getAccessToken())
          storageManager.setRefreshToken(userIsUpdated["refresh_token"])
          // console.log("storageManager.getRefreshToken() : ", storageManager.getRefreshToken())
        }else{
          console.log("FAILED TO UPDATE USER")
          
        }
        console.log("userIsUpdated : ", userIsUpdated)
      } catch (error) {
        console.error( error);
      }
    } else {
      console.log("No changes detected besides ID. Skipping update.");
    }
  } else {
    console.error("User does not exist.");
  }
};

function resetState() {
  state.name = "";
  state.lastName = "";
  state.email = ""; 
  state.username = "";
  state.phoneNumber = "";
  state.firstPassword = ""; 
  state.secondPassword = "";
  state.erroMsg = "";
}


// watch(
//   () =>  state.imagePath,
//   (newVal, oldVal) => {
//     console.log("watch  state.imagePath : ", newVal)
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

input {
  background-color:  rgb(64, 86, 119);
}

textarea {
  background-color: rgb(64, 86, 119);
}


.notEmptyInput {
  border: 8px solid #4CAF50 !important; 
  // background-color: antiquewhite !important;
}
</style>



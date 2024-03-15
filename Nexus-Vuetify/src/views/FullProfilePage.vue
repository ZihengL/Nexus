<template>
  <div v-if="user != null && AllImages.length > 0" :key="user.value" id="fullProfile">
    <form action="#" class="glass">
      <!-- <v-avatar size="10rem"> -->
      <div class="round">
        <img
          :src="getImage(user.picture) || defaultProfilePic"
          alt="Profile Picture"
          class="img_userProfilePic"
        />
        <!-- Dialog Trigger Button -->
        <!-- <v-btn icon @click="galleryDialog = true">
          <v-icon>mdi-image-multiple</v-icon>
        </v-btn> -->
         <btnComp
            :contenu="'Changer Profile'"
            @toggle-btn="galleryDialog = true"
          /> 
        <!--<button class="changerProfileBtn" @click="galleryDialog = true">
          Changer Profile
        </button>-->
      </div>
      <!-- </v-avatar> -->
      <!-- ... Signup form content ... -->
      <!--<<div class="field-container">
        <span class="title">Prénom</span> -->
        <div class="input-group field">
          <input
            class="infos"
            :key="`name-${user.name}`"
            :class="{ notEmptyInput: user.name }"
            type="text"
            :placeholder="user.name || 'Prénom'"
            v-model="state.name"
            required
          />
          <btnComp
            :propClass="'newbtnClass'"
            :contenu="'effacer'"
            @toggle-btn="() => deleteInfo('name')"
          />
        </div>
      <!--<</div>-->

      <!--<div class="field-container">
        <span class="title">Nom</span> -->
        <div class="input-group field">
          <input
            class="infos"
            :key="`lastName-${user.lastName}`"
            :class="{ notEmptyInput: user.lastName }"
            type="text"
            :placeholder="user.lastName || 'Nom'"
            v-model="state.lastName"
          />
          <btnComp
            :propClass="'newbtnClass'"
            :contenu="'effacer'"
            @toggle-btn="() => deleteInfo('lastName')"
          />
        </div>
      <!--</div>-->

      <!--<div class="field-container">
        <span class="title">Téléphone</span> -->
        <div class="input-group field">
          <input
            class="infos"
            :key="`phoneNumber-${user.phoneNumber}`"
            type="tel"
            placeholder="Numero de Téléphone"
            v-model="state.phoneNumber"
            @input="updatephonenumber"
          />
          <btnComp
            :propClass="'newbtnClass'"
            :contenu="'effacer'"
            @toggle-btn="() => deleteInfo('phoneNumber')"
          />
        </div>
      <!--</div>-->

      <!--<div class="field-container">
        <span class="title">Nom Utilisateur</span> -->
        <div class="input-group  field">
          <input
            class="infos"
            :key="`username-${user.username}`"
            :class="{ notEmptyInput: user.username }"
            type="text"
            :placeholder="user.username || 'Nom Utilisateur'"
            v-model="state.username"
          />
        </div>
      <!--</div>-->

      <!--<div class="field-container">
        <span class="title">Description</span> -->
        <div class="input-group  field">
          <textarea
            class="infos"
            :key="`description-${user.description}`"
            :class="{ notEmptyInput: user.description }"
            :placeholder="user.description || 'Description...'"
            v-model="state.description"
          ></textarea>
          <btnComp
            :propClass="'newbtnClass'"
            :contenu="'effacer'"
            @toggle-btn="() => deleteInfo('description')"
          />
        </div>
      <!--</div>-->

      <!--<div class="field-container">
        <span class="title">Mot de Passe</span> -->
        <div class="input-group  field">
          <input
            class="infos"
            type="password"
            placeholder="Mot de passe"
            v-model="state.firstPassword"
          />
        </div>
      <!--</div>-->

      <!--<div class="field-container">
        <<span class="title">Confirmer Mot De Passe</span> -->
        <div class="input-group  field">
          <input
            class="infos"
            type="password"
            placeholder="Confirmer le mot de passe"
            v-model="state.secondPassword"
            required
          />
        </div>
      <!--</div>-->

      <btnComp :contenu="'Modifier'" @toggle-btn="updateUserInfos" />
    </form>

    <div v-if="galleryDialog"  class="roundBorderSmall glass4 dialog">
      <v-icon @click="galleryDialog = false" class="close">mdi-close</v-icon>
      <div class="content">
        <label
          v-for="img, index in AllImages"
          :key="index"
          id="pic"
          class="roundBorderSmall one glass2  "
          @click="state.picture = img"
        >
          <input
            type="radio"
            name="pic"
            :value="img"
          />
          <img :src="img" alt="">
          <p></p>
        </label>
      </div>
    </div>
  </div>


</template>

<script setup>
import btnComp from "../components/btnComponent.vue";
import { defineProps, ref, onMounted, watch, reactive } from "vue";
import storageManager from "../JS/localStorageManager.js";
import defaultProfilePic from "@/assets/Dev_Picture/defaultProfilePic.png";
import { updateData, getOne } from "../JS/fetchServices";

const props = defineProps(["IdDev"]);
let user = ref(null);
let galleryDialog = ref(false);

const state = reactive({
  userToUpdate: "",
  name: "",
  lastName: "",
  email: "",
  username: "",
  picture: "",
  phoneNumber: "",
  firstPassword: "",
  secondPassword: "",
  description: "",
  erroMsg: "",
  MAX_DESC_LENGTH: 5,
  MAX_NAME_LENGTH: 5,
  MAX_LASTNAME_LENGTH: 5,
  MAX_PASSWORD_LENGTH: 5,
});
let AllImages = ref([]);
const updatephonenumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let formattedNumber = "";

  if (input.length > 3 && input.length <= 6) {
    formattedNumber = `(${input.slice(0, 3)}) ${input.slice(3)}`;
  } else if (input.length > 6) {
    formattedNumber = `(${input.slice(0, 3)}) ${input.slice(
      3,
      6
    )}-${input.slice(6, 10)}`;
  } else {
    formattedNumber = input;
  }

  state.phoneNumber = formattedNumber;
};

const getUserInfos = async () => {
  try {
    const dataUser = await getOne("users", "id", storageManager.getIdDev());

    if (dataUser) {
      user.value = dataUser;
      state.userToUpdate = JSON.parse(JSON.stringify(dataUser));
    }
    console.log(" dataUser : ", dataUser);
  } catch (error) {
    console.error("Error fetching user data:", error);
  }
};
const getImage = (image) =>{
    console.log(' get pic,', image)
    let urlPic = '/src/assets/Avatar/' + image;
    console.log('pic,', urlPic)
    return urlPic
  }

  const getAllImages = async (tab) => {
  const folderPath = '/src/assets/Avatar/';
  //const imagePaths = [];
  for (let i = 1; i <= 7; i++) {
    let path = folderPath + 'Avatar_' + i + '.png';
    console.log('path ', path);
    tab.push(`${folderPath}Avatar_${i}.png`);
  }

    //console.log('Nombre de fichiers dans le dossier :', nb);
  console.log('Chemins de toutes les images :', tab);
  return tab
};


const updateUserInfos = async () => {
  const originalUser = user.value;
  // console.log("Original user info:", originalUser.id);
  if (originalUser) {
    let updatedUser = await validateDataBeforeSending();
    try {
      if (updatedUser) {
        console.log("Updating user information...");
        updatedUser.tokens = {
          access_token: storageManager.getAccessToken(),
          refresh_token: storageManager.getRefreshToken(),
        };

        let userIsUpdated = await updateData("users", updatedUser);
        if (userIsUpdated != false) {
          console.log("SUCCESSFULLY UPDATED USER");
          storageManager.setAccessToken(userIsUpdated["access_token"]);
          // console.log("storageManager.getAccessToken() : ", storageManager.getAccessToken())
          storageManager.setRefreshToken(userIsUpdated["refresh_token"]);
          // console.log("storageManager.getRefreshToken() : ", storageManager.getRefreshToken())
          window.location.reload();
        } else {
          console.log("FAILED TO UPDATE USER");
        }
        console.log("userIsUpdated : ", userIsUpdated);
      }
    } catch (error) {
      console.error(error);
    }
  }
};

const deleteInfo = (field) => {
  const fieldValue = user.value[field];
  const isConfirmed = confirm(
    `Êtes-vous sûr de vouloir supprimer ${fieldValue}?`
  );
  if (!isConfirmed) {
    console.log("Deletion canceled by the user.");
    return;
  }

  user.value[field] = null;
};

function validateField(field, currentValue, originalValue) {
  if (!currentValue && !originalValue) {
    return "";
  } else if (currentValue && currentValue !== originalValue) {
    return currentValue;
  } else if (!currentValue && currentValue == originalValue) {
    return currentValue;
  }
  return null;
}

function validateUsername(currentValue, originalValue) {
  if (currentValue && currentValue !== originalValue) {
    return currentValue;
  } else {
    console.error("Nom d'utilisateur ne peut pas être vide");
    return null;
  }
}

function validatePhoneNumber(phoneNumber) {
  if (phoneNumber) {
    return phoneNumber.replace(/\D/g, "");
  }
  return null;
}

function validateDescription(currentValue, originalValue, maxDescLength) {
  if (currentValue.length > maxDescLength) {
    console.error("Mauvaise longueur de description");
    return null;
  } else if (currentValue !== originalValue) {
    return currentValue;
  }
  return null;
}

function validatePasswords(firstPassword, secondPassword) {
  if (
    firstPassword === secondPassword &&
    firstPassword.trim().length > 0 &&
    secondPassword.trim().length > 0
  ) {
    return firstPassword;
  } else {
    console.error("Les mots de passe ne correspondent pas ou sont vides.");
    return null;
  }
}

function compareAndUpdateUser(updatedUser, stateUser) {
  let isDifferent = false;

  console.error(" state.userToUpdate : ", state.userToUpdate);

  for (const key in updatedUser) {
    if (updatedUser.hasOwnProperty(key) && key !== "id") {
      let updatedValue = updatedUser[key] ?? ""; // Use nullish coalescing operator to default to empty string
      let originalValue = stateUser[key] ?? ""; // Use nullish coalescing operator to default to empty string

      // Special handling for fields that can be null
      if (key === "name" || key === "lastName") {
        if (
          (updatedValue === null && originalValue.trim() === "") ||
          (updatedValue.trim() === "" && originalValue === null) ||
          updatedValue.trim() !== originalValue.trim()
        ) {
          console.log(
            `Difference found in ${key}: Updated value is "${updatedValue}", original value was "${originalValue}".`
          );
          isDifferent = true;
          break;
        }
      } else {
        // Convert both values to string and trim, safeguard against undefined values
        updatedValue =
          updatedValue !== null ? updatedValue.toString().trim() : "";
        originalValue =
          originalValue !== null ? originalValue.toString().trim() : "";

        if (updatedValue !== originalValue) {
          console.log(
            `Difference found in ${key}: Updated value is "${updatedValue}", original value was "${originalValue}".`
          );
          isDifferent = true;
          break;
        }
      }
    }
  }

  if (!isDifferent) {
    console.error("No changes detected.");
    user.value = JSON.parse(JSON.stringify(state.userToUpdate));
    // resetState(); // Ensure resetState() is defined elsewhere in your code.
    return null;
  }

  return updatedUser;
}

async function validateDataBeforeSending() {
  let updatedUser = { id: user.value.id };

  // Example usage for a general field
  const fields = ["name", "lastName"];
  fields.forEach((field) => {
    const currentValue = state[field].trim();
    const originalValue = state.userToUpdate[field]?.trim() ?? "";
    const result = validateField(field, currentValue, originalValue);
    // if (result !== null) {
    updatedUser[field] = result;
    // }
  });

  // Username
  const usernameResult = validateUsername(
    state.username.trim(),
    state.userToUpdate.username?.trim() ?? ""
  );
  if (usernameResult) {
    updatedUser.username = usernameResult;
  }

  // Description
  const descriptionResult = validateDescription(
    state.description.trim(),
    state.userToUpdate.description?.trim() ?? "",
    state.MAX_DESC_LENGTH
  );
  if (descriptionResult) {
    updatedUser.description = descriptionResult;
  }

  // Passwords
  const passwordResult = validatePasswords(
    state.firstPassword,
    state.secondPassword
  );
  if (passwordResult) {
    updatedUser.password = passwordResult;
  }

  let sanitizedNumber = validatePhoneNumber(state.phoneNumber.trim());
  if (sanitizedNumber) {
    updatedUser.phoneNumber = sanitizedNumber;
  }

  console.log("updatedUser : ", updatedUser);
  let final_updatedUser = compareAndUpdateUser(updatedUser, state.userToUpdate);
  console.log("final_updatedUser : ", final_updatedUser);
  return final_updatedUser;
}

// function resetState() {
//   state.name = "";
//   state.lastName = "";
//   state.email = "";
//   state.username = "";
//   state.phoneNumber = "";
//   state.firstPassword = "";
//   state.description = "";
//   state.secondPassword = "";
//   state.erroMsg = "";
// }

// watch(
//   () =>  state.imagePath,
//   (newVal, oldVal) => {
//     console.log("watch  state.imagePath : ", newVal)
//   },
//   { deep: true }
// );

onMounted(async () => {
  try {
    await getUserInfos();
    getAllImages(AllImages.value);
    console.log('length ', AllImages.value.length)
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

<style lang="scss">
.round {
  //border-radius: 50%;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  gap: 30%;
  img {
  border-radius: 50%;
  width: 25%;
  margin: 0%;
  }
  // .changerProfileBtn {
  //   flex: 3;
  // }
} 


.dialog {
    position: absolute;
    top: 10%;
    left: 30%;
    width: 40%;
    margin: auto;
  }
  
  .close {
    float: right;
  }
  
  .content {
    margin: 5%;
    display: grid;
    grid-template-columns: auto auto auto;
    gap: 2%;
    padding: 2% 2% 5% 2%;
  }
  
  .one {
    padding: 2% 2% 5% 2%;
    position: relative; /* Ajout de cette ligne */
  }
  
  .one img {
    width: 100%;
  }
  
  .one p {
    position: absolute; /* Ajout de cette ligne */
    top: 0; /* Ajout de cette ligne */
    left: 0; /* Ajout de cette ligne */
    width: 100%; /* Ajout de cette ligne */
    height: 100%; /* Ajout de cette ligne */

    /* From https://css.glass */
    background: rgba(255, 255, 255, 0.62);
    //border-radius: 0px;
    backdrop-filter: blur(0px);
    -webkit-backdrop-filter: blur(0px);
    border: 1px solid rgba(255, 255, 255, 0); /* Ajout de cette ligne */
    display: none; /* Ajout de cette ligne */
  }
  
  .one input {
    display: none;
  }
  .one:hover p, .one input:checked ~ p {
    display: block; /* Ajout de cette ligne */
  }

#fullProfile {
  width: 100%;
  margin: 0 auto 3% auto;
  padding: 5%;
  // margin-top: 10%;
  // margin-bottom: 10%;
  form {
    display: flex;
    flex-direction: column;
    width: 50%;
    padding: 2%;
    margin: auto;
    .field-container {
      margin: 0%;
      display: inline-block;
      border: 2px solid red;
    }

    .infos {
      width: 200%;
      font-size: 1em;
    }
    .input-group {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .title {
      display: block;
      text-align: center;
      color: rgb(255, 255, 255);
      font-weight: bold;
      // margin-bottom: 1%;
      // margin-top: 8%;
    }
    input,
    textarea {
      flex-grow: 1; // Allow input and textarea to fill available space
      margin-right: 0%;
      background-color: rgb(64, 86, 119);
      font-size: 1.5rem;
    }
    .notEmptyInput {
      // border: 2px solid #4caf50 !important;
      font-size: 1em;
      &::placeholder {
        color: #ffffff;
        opacity: 1;
        padding-left: 2%;
        // font-size: 1em;
      }
    }
  }
}

.newbtnClass {
  height: 100%;
  width: 50%;
  position: relative;
  overflow: hidden;
  margin-left: 2%;

  .btn-layer {
    height: 100%;
    width: 300%;
    position: absolute;
    margin: auto;
    left: -100%;
    // background: -webkit-linear-gradient(right, var(--purple), pink, yellow);
    background: -webkit-linear-gradient(
      right,
      var(--purple),
      var(--dark-blue),
      var(--purple),
      var(--dark-blue)
    );
    border-radius: 5%;
    transition: all 0.4s ease;
  }
  .submit {
    height: 100%;
    width: 100%;
    z-index: 1;
    position: relative;
    background: none;
    border: none;
    color: var(--light);
    padding-left: 0;
    border-radius: 5px;
    font-size: 1em;
    font-weight: 500;
    cursor: pointer;
  }
}
.fieldBtn:hover {
  .btn-layer {
    left: 0;
  }
  .submit {
    text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
      0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
    animation: neonGlow 1.5s ease-in-out infinite alternate;
  }
}

// .changerProfileBtn {
//   height: 60%;
//   width: 50%;
//   // position: relative;
//   overflow: hidden;
//   // padding: 5%;
//   // display: flex;
//   align-items: center;
//   border-radius: 20px;
//   background: -webkit-linear-gradient(
//       right,
//       var(--purple),
//       var(--dark-blue),
//       var(--purple),
//       var(--dark-blue)
//     );
//     color: #ffffff;
// }
</style>

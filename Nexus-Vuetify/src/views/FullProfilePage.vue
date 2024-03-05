<template>
  <div v-if="user" id="fullProfile">

    <form action="#" class=" glass">
          
          <v-avatar size="10rem">
            <v-img
              src="../assets/Rich_Ricasso.png"
              alt="John"
            ></v-img>
          </v-avatar>
          <!-- ... Signup form content ... -->
          <div class="field field2">
            <input type="text" :placeholder="user.user"  required>
            <input type="text" placeholder="Prenom *"  required>
          </div>
          <div class="field">
            <input type="text" placeholder="Téléphone" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Username" required>
          </div>
          <div class="field">
            <input type="text" placeholder="Email" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Mot de passe" required>
          </div>
          <div class="field">
            <input type="password" placeholder="Confirmer le mot de passe" required>
          </div>
          <btnComp :contenu="'Modifier'" @toggle-btn="toggleLogout"/>
        </form>
  </div>
</template>
<script>
  import btnComp from "../components/btnComponent.vue"
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

  form{
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
<script setup>
import { defineProps, ref, onMounted } from 'vue';
import { fetchData } from '../JS/fetch';
import { create, getOne } from "../JS/fetchServices";

const props = defineProps(['IdDev']);

const  username = ref(null);
const  email = ref(null);
const  bio = ref(null);
const  isUsernameValid = true;
const  isEmailValid = true;
const  isBioValid = true;

const user = ref(null);
onMounted(async () => {
    try {
      const dataUser = await fetchData("users", "getOne", "id",  props.IdDev, null, null, null, "GET");
      //  await getOne("users", "id", props.IdDev)
      user.value = dataUser;
      //console.log('LeGame : ' , LeGame._rawValue.developerID)    
    } catch (error) { 
      console.error('Error fetching data:', error);
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


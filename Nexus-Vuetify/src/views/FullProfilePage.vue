<template>
  <div id="fullProfile">

    <form action="#" class=" glass">
          
          <v-avatar size="10rem">
            <v-img
              src="../assets/Rich_Ricasso.png"
              alt="John"
            ></v-img>
          </v-avatar>
          <!-- ... Signup form content ... -->
          <div class="field">
            <input type="text" placeholder="Nom">
          </div>
          <div class="field">
            <input type="text" placeholder="Prenom">
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
          <div class="fieldBtn">
            <div class="btn-layer"></div>
            <v-btn density="default" class="submit glow" @click="toggleProfile">
              Modifier
            </v-btn>
          </div>
        </form>
  </div>
</template>

<style lang="scss" scoped>
#fullProfile {
  width: 100%;
  margin: 0% auto 3% auto;
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

  }
}


</style>
<script>
export default {
  data() {
    return {
      username: '',
      email: '',
      bio: '',
      isUsernameValid: true,
      isEmailValid: true,
      isBioValid: true,
    };
  },
  methods: {
    saveProfile() {
      this.isUsernameValid = !!this.username;
      this.isEmailValid = !!this.email;
      this.isBioValid = !!this.bio;
      
      if (this.isUsernameValid && this.isEmailValid && this.isBioValid) {
        alert('Profile saved successfully!');
        // Implement the save logic here
      }
    }
  }
}
</script>


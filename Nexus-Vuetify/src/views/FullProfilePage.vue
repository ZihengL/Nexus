<template>
  <div id="app">
    <div class="profil">
      <img src="../assets/img/profile.png" alt="bugmanguy logo" style="width: 100px; display: block; margin: 0 auto; background-color: blue;"/>
    </div>
    <section class="form-section">
      <div>
        <input type="text" id="username" placeholder="Username" required>
      </div>
      <div>
        <input type="password" id="mdp" placeholder="Mot de passe" required>
      </div>
      <div>
        <input type="password" id="mdp" placeholder="Confirmer" required>
      </div>
      <div class="bio">
        <textarea id="bio" placeholder="Bio"></textarea>
      </div>
      <div class="btn">
        <button>Sauvegarder</button>
      </div>
    </section>
  </div>
</template>

<style scoped>
body {
  font-family: 'Arial', sans-serif;
  background-color: #1b2838;
  color: #c7d5e0;
  margin: 0;
  padding: 20px;
}

.btn {
  display: flex; 
  justify-content: center; 
  margin: 40px auto; 
  width: fit-content; 
}
.info{
  margin-top: 10px;
}

button {
  padding: 20px 40px; /* Increase padding to make the button taller and wider */
  background-color: #002D62;
  color: #fff;
  border: none;
  border-radius: 40px;
  cursor: pointer;
  font-size: 18px; /* Increase font size for better visibility */
  display: inline-block; /* Ensure the button respects padding and sizing */
  margin: 0 auto; /* Center the button if it's not already */
  transition: background-color 0.3s; /* Smooth transition for hover effect */
}

#app {
  max-width: 80%;
  max-height: 90%;
  margin: 20px auto;
  background-color: #13274F;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  height: 95svh;
}

.bio{
  margin-top: 50px;
}

input,
textarea {
  display: block;
  width: calc(100% - 20px);
  height: 50px; 
  padding: 5px;
  background-color: #002244;
  border: 1px solid #4a6f7c;
  border-radius: 4px;
  color: #c7d5e0;
  margin-top: 40px;
}

#bio{ 
   display: block;
  width: calc(100% - 20px); 
  height: 200px; 
  padding: 10px; 
  background-color: #002244;
  border: 1px solid #4a6f7c;
  border-radius: 4px;
  color: #c7d5e0;
  margin-top: 5px; 
  font-size: 16px; 

}

.profil > img {
  width: 500px;
  display: block;
  margin: 0 auto;
  background-color: blue;
  border-radius: 50%;
}

button:hover {
  background-color: #0039a6;
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


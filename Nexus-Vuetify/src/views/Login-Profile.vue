<template>
  <div class="loginContainer">
    <LoginRegister v-if="isProfileVisible" @showProfile="toggleProfileForm "/>
    <Profile  v-else @showLogin="toggleLoginForm"/>
  </div>
</template>
<script setup>
  import LoginRegister from '/src/components/LoginRegister.vue';
  import Profile from '/src/components/Profile.vue';

  import { ref } from 'vue';
  // Récupérer la valeur depuis le stockage local
  /*const storedIsConnected = localStorage.getItem('isConnected');

  // Utiliser la valeur du stockage local ou attribuer false par défaut
  var isConnected = ref(storedIsConnected !== null ? storedIsConnected : false);*/

	const isProfileVisibleTemp = ref(localStorage.getItem("profileVisible"))
  const isProfileVisible = ref(isProfileVisibleTemp .value !== true ? isProfileVisibleTemp .value : false)
	
  console.log(isProfileVisible.value)
	const toggleProfileForm = () => {
		isProfileVisible.value = false
		localStorage.setItem("profileVisible", isProfileVisible.value.toString());
    console.log("toogle : " + isProfileVisible.value)
	};
  const toggleLoginForm = () => {
		isProfileVisible.value = true
		localStorage.setItem("profileVisible", isProfileVisible.value.toString());
    console.log("toogle : " + isProfileVisible.value)
	};

</script>


<style scoped>
  .loginContainer {
    text-align: center;
    padding: 1% 0% 2% 0%;
  }
  
</style>
<template>
  <div class="loginProfile">
    <LoginRegister v-if="isProfileVisible" @showProfile="toggleProfileForm "/>
    <Profile v-else @showLogin="toggleLoginForm" :isHimself="true" :idDevl="devsId"/>
  </div>
</template>

<script setup>
  import LoginRegister from '/src/components/LoginRegister.vue';
  import Profile from '/src/components/Profile.vue';
  import { ref, getCurrentInstance } from 'vue';

  const { proxy } = getCurrentInstance();
  const isConnected = ref(true);
  
  const devsId = 3;
  console.log('id devs ', devsId);
	const isProfileVisibleTemp = ref(localStorage.getItem("profileVisible"))
  const isProfileVisible = ref(isProfileVisibleTemp .value !== true ? isProfileVisibleTemp .value : false)
	
  const toggleProfileForm = () => {
    isProfileVisible.value = false;
    isConnected.value = true;
    proxy.$isConnected = isConnected.value; // Assurez-vous que $isConnected est mis à jour ici
    localStorage.setItem("profileVisible", isProfileVisible.value.toString());
  };

  const toggleLoginForm = () => {
    isProfileVisible.value = true;
    isConnected.value = false;
    proxy.$isConnected = isConnected.value; // Assurez-vous que $isConnected est mis à jour ici
    localStorage.setItem("profileVisible", isProfileVisible.value.toString());
  };
</script>


<style lang="scss" scoped>
  .loginProfile {
    text-align: center;
    padding: 1% 0% 2% 0%;
  }
  
</style>
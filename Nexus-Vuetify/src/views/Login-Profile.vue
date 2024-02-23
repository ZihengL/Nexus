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
  import { inject } from 'vue';

  //const { proxy } = getCurrentInstance();
  //const isConnected = ref(true);

const isConnected = inject('isConnected');
const toggleConnection = inject('toggleConnection');


  
  const devsId = 3;
  console.log('id devs ', devsId);
	const isProfileVisibleTemp = ref(localStorage.getItem("profileVisible"))
  const isProfileVisible = ref(isProfileVisibleTemp .value !== true ? isProfileVisibleTemp .value : false)
	
  const toggleProfileForm = () => {
    isProfileVisible.value = false;
    toggleConnection(); // Corrigé pour appeler la fonction
    console.log('glob 2 ', isConnected.value)
    localStorage.setItem("profileVisible", isProfileVisible.value.toString());
};

const toggleLoginForm = () => {
    isProfileVisible.value = true;
    toggleConnection(); // Corrigé pour appeler la fonction
    console.log('glob 2', isConnected.value)
    localStorage.setItem("profileVisible", isProfileVisible.value.toString());
};

</script>


<style lang="scss" scoped>
  .loginProfile {
    text-align: center;
    padding: 1% 0% 2% 0%;
  }
  
</style>
<template>
  <div class="loginProfile">
    <LoginRegister v-if="!isConnected" @showProfile="toggleProfileForm "/>
    <Profile v-else @showLogin="toggleLoginForm" :isHimself="true" :idDevl="devsId"/>
  </div>
</template>

<script setup>
  import LoginRegister from '../components/login/LoginRegister.vue';
  import Profile from '../components/login/Profile.vue';
  import { defineProps, ref, watch } from 'vue';

  const props = defineProps(['connectedView']);
  let isConnected = ref(false);

  isConnected.value = props.connectedView;

  watch(() => props.connectedView, (newValue, oldValue) => {
    isConnected.value = props.connectedView;
    console.log('var update : ', newValue);
  });

  const emit = defineEmits(['changeCon'])
  const changeConnexion = () => {
    emit('changeCon')
  };
  
  const devsId = 3;
	
  const toggleProfileForm = () => {
    changeConnexion();
  };

  const toggleLoginForm = () => {
    changeConnexion();
  };

</script>

<style lang="scss" scoped>
  .loginProfile {
    text-align: center;
    padding: 1% 0% 2% 0%;
  }
</style>
<template>
  <v-main>
    <router-view @changeCon="changeValeur" :connectedView="connectValue"/>
  </v-main>
</template>

<script setup>
import { defineProps, ref, watch } from 'vue';

const emit = defineEmits(['changeVal'])
const props = defineProps(['connected']);

let connectValue = ref(false);
connectValue.value = props.connected;

const changeValeur = () => {
  emit('changeVal')
};

watch(() => props.connected, (newValue, oldValue) => {
  console.log('var update view : ', newValue);
  connectValue.value = props.connected;
});
</script>

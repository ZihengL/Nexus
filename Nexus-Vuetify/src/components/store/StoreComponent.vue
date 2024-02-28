<template>
    <div v-if="listeJeux" id="storeComp" class="glass">
      <GameVue v-for="game in listeJeux" :key="game.id" :idGame="game.id" class="vuee" />
    </div>
  </template>
  
  <script setup>
  import GameVue from './StoreGameVue.vue';
  import { fetchData } from '../../JS/fetch';
  import { ref, onMounted } from 'vue';
  
  let listeJeux = ref(null);
  
  onMounted(async () => {
   try {
      const filters = {
        id: { gt: 0, lte: 20 },
      };
  
      const sorting = {
        id: false,
      };
  
      const includedColumns = ['id'];
      const jsonBody = { filters, sorting, includedColumns };
  
      listeJeux.value = await fetchData('games', 'getAllMatching', null, null, null, null, jsonBody, 'POST')
       .then((data) => {
         listeJeux.value = data;
        //console.log('data : ', data)
        //console.log('listeJeux : ', listeJeux.value)
       })
       .catch((error) => {
          //Handle errors if any
         console.error('Error fetching data:', error);
       });
   } catch (error) {
     console.error('Error fetching data:', error);
   }
 });
  </script>
  

<style lang="scss">
    #storeComp { 
        padding: 0%;
        width: 100%;
        display: grid;
        grid-template-columns: auto auto;
        gap: 2%;
        padding: 2% 2% 5% 2%;

    }
</style>
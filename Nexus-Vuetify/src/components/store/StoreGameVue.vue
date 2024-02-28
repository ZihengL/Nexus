<template>
    <router-link v-if="LeGame && devName" :to="{ name: 'Game', params: { idGame: LeGame.id } }" class="glass2  roundBorderSmall">
        <div class="img roundBorderSmall">
            <img src="../../assets/img/dontstarve.png" alt="#" class=" roundBorderSmall">
            <p  class=" roundBorderSmall">
                {{ LeGame.description }}
            </p>
        </div>
        <h3>{{ LeGame.title }}</h3>
        <h4>{{ devName }}</h4>
        <div class="ratings">
            <v-rating
                hover
                half-increments
                :length="5"
                :size="32"
                :model-value="LeGame.ratingAverage"
                active-color= rgba(3,33,76,1)
                class="rat"
            />
        </div>
        <ul >
            <li class="glow">Tag</li>
            <li class="glow">Tag</li>
            <li class="glow">Tag</li>
        </ul>
    </router-link>
</template>

<script setup>

import { fetchData } from '../../JS/fetch'
import { ref, onMounted } from 'vue';

const props = defineProps(['idGame']);
let LeGame = ref(null);
let devName = ref(null);

onMounted(async () => {
    try {
         const dataGame = await fetchData("games", "getOne", "id",  props.idGame, null, null, null, "GET");

        LeGame.value = dataGame;
        //console.log('LeGame : ', LeGame.value)

        if(LeGame.value){
            const devId =  LeGame.value.developerID
            //console.log('devId : ' , devId)
            
            const filters = {
            id: devId,
            }
            const sorting = {
            id: false
            }

            const includedColumns = ['id', 'username']
            const jsonBody = { filters, sorting, includedColumns }
            
            const dataDevs = await fetchData("users", "getAllMatching", null, null, null, null,  jsonBody, "POST");
            devName.value = dataDevs;
            //console.log('devs : ' , devName)
        }
     } catch (error) { 
       console.error('Error fetching data:', error);
     }
  });
</script>

<style lang="scss">
.glass2 {
    text-decoration: none;
    color: var(--light-trans-2);
    padding-bottom: 2%;
    .img {
        flex: 5;
        position: relative;

        img {
            width: 100%;
            display: inline-block;
            transition: opacity 0.3s ease; /* Ajout de la transition d'opacité */
            opacity: 1; /* L'image est initialement complètement opaque */
        }

        p {
            display: none;
            position: absolute;
            text-align: center;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2; /* Assure que le texte apparaît au-dessus de l'image */
        }
    }



    ul {
        display: flex;
        justify-content: space-around;
        flex-direction: row;
        list-style: none;
    }
}
.glass2:hover {
    .img img {
        opacity: 0.3; /* Réduit l'opacité de l'image lorsqu'elle est survolée */
    }

    .img p {
        display: inline-block;
        width: 90%;
    }
}
</style>

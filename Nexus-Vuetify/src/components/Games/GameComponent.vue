<template>
    <v-card :id="game.id" class='game'>
        <h1>{{ game.title }}</h1>
        <p>{{ game.description }}</p>
    </v-card>
</template>


<script setup>
import { defineProps, ref, watchEffect } from "vue";
import fetchServices from '../../JS/fetchServices';

const game = defineProps({
    id: Number,
    developerID: Number,
    stripeID: Number,
    title: String,
    description: String,
    files: String,
    media: String,
    releaseDate: Date,
    ratingAverage: Number
});

const developer = ref(null)

watchEffect(() => {
    if (game.developerID) {
        developer = fetchServices.getUser(game.developerID)
    }
})

</script>
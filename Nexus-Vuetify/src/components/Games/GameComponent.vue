<template>
    <v-card :id="game.id" class='game'>
        <h1>{{ game.title }}</h1>
        <p>{{ game.description }}</p>
    </v-card>
</template>


<script setup>
import { defineProps } from "vue";

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

const getDeveloper = async () => {
    const data = null;
};

</script>
<template>
    <div class="gameCart">
        <v-card class="unJeu">
        <div class="imgContainer">
            <!-- Utilisation de la prop pour l'image -->
            <img :src="image" alt="" class="img">
        </div>
        <v-card-text class="nomJeu">{{ name }}</v-card-text>
        <v-card-text class="prixJeu">{{ price }}</v-card-text>
        <v-icon icon="mdi-delete" style="vertical-align: middle" size="70" @click="removeGame"/>
        </v-card>
    </div>
</template>

<script>
export default {
    props: {
        // Définition des props
        image: String,
        name: String,
        price: String,
    },
    methods: {
        // Ajoutez la logique pour supprimer le jeu ici
        removeGame() {
        this.$emit('remove-game');
        },
    },
};
</script>
  
  
<style>
    .gameCart {
        border: 2px solid red;
        display: inline-block;
        width: 100%;
        margin: 1% 0%;
    }
    .gameCart .unJeu {
        border: 2px solid rgb(21, 93, 226);
        width: 100%;
        display: flex;
        flex-direction: row;
    }
    .gameCart .unJeu .imgContainer {
        border: 2px solid rgb(0, 0, 0);
        width: 10%;
    }
    .gameCart .unJeu .imgContainer img{
        width: 100%;
        height: 100%;
        border: 2px solid rgb(7, 219, 60);
    }
    .gameCart .unJeu .nomJeu,
    .gameCart .unJeu .prixJeu {
        border: 2px solid rgb(166, 219, 7);
        text-align: center;
        padding: 2% 0%;
    }
</style>
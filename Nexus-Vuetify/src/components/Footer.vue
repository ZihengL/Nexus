<template>
    <footer class="glass">
        <ul>
            <router-link to="/" class="footerLink glow" title="Accueil">Accueil</router-link>
            <router-link to="/Store" class="footerLink glow" title="Catalogue">Boutique</router-link>
            <router-link to="/About" class="footerLink glow" title="Infolettre">À propos</router-link>
            <router-link v-if="!connected" to="/Login" class="footerLink glow" title="Panier">Connexion</router-link>
            <router-link v-else to="/Login" class="footerLink glow" title="Panier" @click="changeConnexion">Déconnexion</router-link>
        </ul>
        <p class="golden">&copy Radtz</p>
    </footer>
</template>

<script setup>
import { defineProps, ref, watch } from 'vue';

const emit = defineEmits(["changeCon"]);
const props = defineProps(['connected']);

const changeConnexion = () => {
  emit("changeCon");
};
watch(() => props.connected, (newValue, oldValue) => {
  console.log('var update : ', newValue);
});

</script>

<style src="../styles/FooterStyle.scss" scoped></style>

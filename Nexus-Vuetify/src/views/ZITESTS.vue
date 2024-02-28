<template>
    <div class="basicdiv">
        <gameComponent></gameComponent>
    </div>
</template>

<script>
import { defineProps } from "vue";
import GameComponent from '../components/Games/GameComponent.vue';
import { fetchData } from "../JS/fetch";

export default {
    components: {
        GameComponent,
    },
    methods: {
        getGame() {
        }
    }
}


</script>

<style lang="scss" scoped>
.basicdiv {
    display: flex;
    margin: auto;
    text-align: center;
    flex-direction: column;
    justify-items: center;
    align-items: center;
}
</style>
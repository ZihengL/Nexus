<template>
    <div id="storeComp" class="glass">
        <GameVue :link="'/Game'"/>
        <GameVue :link="'/Game'"/>
        <GameVue :link="'/Game'"/>
        <GameVue :link="'/Game'"/>
        <GameVue :link="'/Game'"/>
    </div>
</template>
<script>
    import GameVue from '../components/StoreGameVue.vue';
    export default {
        components: {
            GameVue,
        },
        data() {
            return {
                //
            };
        },
        methods: {
        // Define the method to handle icon click
        },
    };
</script>

<style lang="scss">
    #storeComp {  
        display: grid;
        grid-template-columns: auto auto;
        gap: 2%;
        padding: 2% 2% 5% 2%;
    }
</style>
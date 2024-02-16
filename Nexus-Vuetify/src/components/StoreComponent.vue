<template>
    <div id="storeComp" class="glass">
        <GameVue :link="'/Game'" class="vuee"/>
        <GameVue :link="'/Game'" class="vuee"/>
        <GameVue :link="'/Game'" class="vuee"/>
        <GameVue :link="'/Game'" class="vuee"/>
        <GameVue :link="'/Game'" class="vuee"/>
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
        /*border: 2px solid black; 
        display: flex;
        flex-direction: row;
        padding: 0%;*/

        display: grid;

        grid-template-columns: auto auto;
        gap: 2%;
        padding: 2% 2% 5% 2%;
        width: 100%;

    }
</style>
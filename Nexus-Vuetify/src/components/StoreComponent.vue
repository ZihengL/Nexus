<template>
    <div  id="storeComp" class="glass" >
        <div v-for="(item, index) in listeJeux" :key="index">
            <GameVue :idGame="listeJeux.id" class="vuee"/>
        </div>
    </div>
</template>
<script>
    import GameVue from '../components/StoreGameVue.vue';
    import { fetchData } from '../JS/fetch'
    export default {
        components: {
            GameVue,
        },
        data() {
            return {
                listeJeux: [],
            };
        },
        methods: {
        // Define the method to handle icon click
        },
        mounted () {
            const includedColumns = ['id']
            const jsonBody = { includedColumns }

            fetchData('games', 'getAll', null, null, jsonBody, 'POST')
                .then(data => {
                    this.listeJeux = data;

                    console.log('data : ', this.listeJeux);
                }
            )
            .catch(error => {
            console.error('Error fetching data:', error);
            });
        }
    };
</script>

<style lang="scss">
    #storeComp { 
        padding: 0%;
        width: 100%;
        div {
            width: 100%;
            display: grid;
            grid-template-columns: auto auto;
            gap: 2%;
            padding: 2% 2% 5% 2%;
        }

    }
</style>
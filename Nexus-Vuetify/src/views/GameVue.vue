<template>
    <div id="gameVue">
        <div class="content">
            <game class="gameCarrousel"/>
            <div class="gameInfo">
                <div class="gameImg">
                    <img src="../assets/image/img1.png" alt="#">
                </div>
                <div class="descript">
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing
                        elit. Vel debitis illum reprehenderit recusandae 
                        reiciendis quasi magni eum harum minus ipsam sint optio 
                        quas expedita doloremque maiores enim minima, voluptas unde.</p>
                </div>
                <div class="ratings">
                    <v-rating
                        hover
                        :length="5"
                        :size="32"
                        :model-value="3"
                        active-color="primary"
                        class="rat"
                    />
                </div>
                <div class="devs">
                    <p><b>Developeur : </b> 2Braise</p>
                </div>
                <div class="tags">
                    <a href="#">Fps</a>
                    <a href="#">Aventure</a>
                    <a href="#">Drame</a>
                    <a href="#">Algerient</a>
                </div>
                <div class="field btn">
                    <div class="btn-layer"></div>
                    <v-btn density="default" class="submit" @click="toggleProfile">
                        Telecharger
                    </v-btn>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
    import game from '../components/GameCarrousel.vue'
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>
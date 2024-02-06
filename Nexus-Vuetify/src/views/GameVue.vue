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
                    ddd
                </div>
                <div class="devs">
                    dd
                </div>
                <div class="tags">
                    ddd
                </div>
            </div>
        </div>
        <div class="btn"></div>

    </div>
</template>

<script setup>
    import game from '../components/GameCarrousel.vue'
</script>

<style src="../styles/GameVueStyle.scss" lang="scss"></style>
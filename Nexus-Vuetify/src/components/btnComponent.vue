<template>
    <div class="fieldBtn roundBorderSmall">
        <div class="btn-layer"></div>
        <v-btn density="default" class="submit" @click="toggleBtn">
            {{contenu}}
        </v-btn>
    </div>
</template>

<script setup>
    import { defineProps } from 'vue';

    const props = defineProps(['contenu']);
    const emit = defineEmits(['toggle-btn'])
    console.log(props.contenu);

    const toggleBtn = () => {
        // Ajoutez votre logique de bascule (toggle) ici si nécessaire

        // Émettez un événement pour informer le parent de la bascule
        emit('toggle-btn');
    };
</script>

<style lang="scss">
.fieldBtn{
    height: 50px;
    width: 100%;
    position: relative;
    overflow: hidden;

    .btn-layer{
        height: 100%;
        width: 300%;
        position: absolute;
        left: -100%;
        background: -webkit-linear-gradient(right, var(--purple), var(--dark-blue), var(--purple), var(--dark-blue));
        border-radius: 5px;
        transition: all 0.4s ease;;
    }
    .submit{
        height: 100%;
        width: 100%;
        z-index: 1;
        position: relative;
        background: none;
        border: none;
        color: var(--light);
        padding-left: 0;
        border-radius: 5px;
        font-size: 20px;
        font-weight: 500;
        cursor: pointer;
    }
}
.fieldBtn:hover {
    .btn-layer{
        left: 0;
    }
    .submit {
        
        text-shadow:
            0 0 7px #fff,
            0 0 10px #fff,
            0 0 21px #fff,
            0 0 42px #0fa,
            0 0 82px #0fa,
            0 0 92px #0fa,
            0 0 102px #0fa,
            0 0 151px #0fa;
        animation: neonGlow 1.5s ease-in-out infinite alternate;
    }
}
</style>
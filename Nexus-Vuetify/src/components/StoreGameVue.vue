<template>
    <router-link :to="link" class="glass2">
        <div class="img">
            <img src="../assets/img/dontstarve.png" alt="#">
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex,
                nam distinctio velit laborum quo qui ullam in? Hic corporis
                dolorem dolores expedita vitae! Vero dolores commodi aliquam 
                explicabo placeat sequi.
            </p>
        </div>
        <h3>Title</h3>
        <h4>Developeur</h4>
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
        <ul >
            <li class="glow">Tag</li>
            <li class="glow">Tag</li>
            <li class="glow">Tag</li>
        </ul>
    </router-link>
</template>

<script>
export default {
    props: {
        link: String,
    },
    methods: {
        removeGame() {
            this.$emit('remove-game');
        },
    },
};
</script>

<style lang="scss">
.glass2 {
    text-decoration: none;
    color: var(--light-trans-2);
    padding-bottom: 2%;
    .img {
        flex: 5;
        position: relative;

        img {
            width: 100%;
            display: inline-block;
            transition: opacity 0.3s ease; /* Ajout de la transition d'opacité */
            opacity: 1; /* L'image est initialement complètement opaque */
        }

        p {
            display: none;
            position: absolute;
            text-align: justify;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2; /* Assure que le texte apparaît au-dessus de l'image */
        }
    }



    ul {
        display: flex;
        justify-content: space-around;
        flex-direction: row;
        list-style: none;
    }
}
.glass2:hover {
    .img img {
        opacity: 0.3; /* Réduit l'opacité de l'image lorsqu'elle est survolée */
    }

    .img p {
        display: inline-block;
        width: 90%;
    }
}
</style>

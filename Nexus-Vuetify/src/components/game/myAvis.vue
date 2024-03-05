<template>
    <div class="containerFormAvis">
        <input type="text" id="AvisId" placeholder="Votre Avis..." class="SaisieText glass roundBorderSmall" />
        <div class="starContainer">
          <h3>Votre Évaluation:</h3>
          <div class="ratings">
            <v-rating hover :length="5" :size="32" :model-value="3" active-color="primary" class="rat" />
          </div>
        </div>
        <btnComp :contenu="'Envoyer votre Avis'" @toggle-btn="toggleProfile"/>
      </div>
</template>
<script setup>
    import btnComp from '../btnComponent.vue';
</script>
<style lang="scss">
  .containerFormAvis {
    margin-top: 5%;

    .starContainer {
        margin-top: 2%;
        display: flex;
        align-items: center;
        justify-content: center;
        h3 {
        color: var(--light);
        }
    }
    .SaisieText {
        border: 1px solid white;
        padding: 2%;
        width: 100%;
        height: auto;
        bottom: 0px;
    }
  }
</style>
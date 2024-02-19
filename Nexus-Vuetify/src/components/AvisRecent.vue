<template>
  <div class="ratingLast">
    <h2>Avis par les derniers sortie</h2>
    <ul>
      <li v-for="avis in avisRecents" :key="avis.id">
        {{ avis.text }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      avisRecents: [ // Supposez que vous avez des données d'avis récents
        { id: 1, text: "Super produit!" },
        { id: 2, text: "Excellent service client!" },
        { id: 3, text: "Livraison rapide et efficace!" }
      ]
    };
  }
};
</script>

<style>
.ratingLast{
  flex-basis: 200%;
}
ul{
  list-style-type: none;
}
</style>

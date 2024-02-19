<template>
  <div class="AvisRating">
    <h2>Avis par la note donnée</h2>
    <ul>
      <li v-for="avis in avisParNote" :key="avis.id">
        {{ avis.text }} - Note: {{ avis.note }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      avisParNote: [ // Supposons que vous avez des données d'avis par note
        { id: 1, text: "Super produit!", note: 5 },
        { id: 2, text: "Service client médiocre", note: 2 },
        { id: 3, text: "Livraison rapide mais produit défectueux", note: 3 }
      ]
    };
  }
};
</script>

<style>
.AvisRating {
  flex-basis: 100%;
}
</style>

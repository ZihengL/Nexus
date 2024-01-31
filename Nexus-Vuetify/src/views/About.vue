<template>
  <h1>A propos</h1>
  <div id="app">
    <!-- Barre de navigation latérale -->
    <div class="sidebar">
      <ul>
        <li @click="selectedTab = 'Zi'">Zi</li>
        <li @click="selectedTab = 'Rebecca'">Rebecca</li>
        <li @click="selectedTab = 'Thomas'">Thomas</li>
        <li @click="selectedTab = 'Debraise'">Debraise</li>
        <li @click="selectedTab = 'Amazigh'">Amazigh</li>
      </ul>
    </div>

    <!-- Contenu changeant en fonction de la navigation -->
    <div class="content">
      <div v-if="selectedTab === 'Zi'">
        <Zi/>
      </div>
      <div v-else-if="selectedTab === 'Rebecca'">
        <h1>À propos de nous</h1>
        <p>Nous sommes une entreprise formidable.</p>
      </div>
      <div v-else-if="selectedTab === 'Thomas'">
        <h1>Contactez-nous</h1>
        <p>Vous pouvez nous contacter à l'adresse email@example.com.</p>
      </div>
      <div v-else-if="selectedTab === 'Debraise'">
        <h1>Contactez-nous</h1>
        <p>Vous pouvez nous contacter à l'adresse email@example.com.</p>
      </div>
      <div v-else-if="selectedTab === 'Amazigh'">
        <h1>Contactez-nous</h1>
        <p>test.</p>
      </div>
    </div>
  </div>

</template>
<script scoped>
import Zi from  '../components/Zi.vue';
import Thomas from '../components/Thomas.vue';
import  Debraise from "../components/Debraise.vue";
import  Amazigh from "../components/Amazigh.vue";
import Rebecca  from "../components/Rebecca.vue";
export default{

  data(){
    return{
      selectedTab: '',
Zi,
Thomas,Amazigh,Debraise,Rebecca
    }
  }
}
</script>
<style>
/* Style pour la navigation */
.sidebar {
  width: 200px;
  height: 100%;
  background-color: #f0f0f0;
  float: left;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
}

.sidebar li {
  padding: 10px;
  cursor: pointer;
  border-bottom: 1px solid #ccc;
}

.sidebar li:hover {
  background-color: #ddd;
}

.content {
  margin-left: 220px;
  padding: 20px;
}
</style>



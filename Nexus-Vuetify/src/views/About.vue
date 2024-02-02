<template>
  <h1>A propos</h1>
  <div id="app">
    <!-- Barre de navigation latérale -->
    <div class="sidebar">
      <ul>
        <li @click="selectedTab = 'Zi'">Zi Heng Liu</li>
        <li @click="selectedTab = 'Rebecca'">Rebecca </li>
        <li @click="selectedTab = 'Thomas'">Thomas</li>
        <li @click="selectedTab = 'Debraise'">Debraise</li>
        <li @click="selectedTab = 'Amazigh'">Amazigh</li>
      </ul>
    </div>

    <!-- Contenu changeant en fonction de la navigation -->
    <div class="content1">
      <div v-if="selectedTab === 'Zi'">
        <ZiH>
          <p>Je suis Zi Heng Liu, ingénieur en informatique et je suis chargé du développement frontend de l'application.
            Pour plus d'informations </p>
        </ZiH>
      </div>
      <div v-else-if="selectedTab === 'Rebecca'">
        <RebeccaM>
          <p>Reb</p>
        </RebeccaM>
      </div>
      <div v-else-if="selectedTab === 'Thomas'">
        <ThomasP>
          <p>thom</p>
        </ThomasP>
      </div>
      <div v-else-if="selectedTab === 'Debraise'">
        <DebraiseM>
          <p>debraise</p>
        </DebraiseM>
      </div>
      <div v-else-if="selectedTab === 'Amazigh'">
        <AmazighM>
          <p>amazon</p>
        </AmazighM>
      </div>
    </div>
  </div>
</template>
<script scoped>
import AmazighM from "../components/Amazigh.vue";
import DebraiseM from "../components/Debraise.vue";
import RebeccaM from "../components/Rebecca.vue";
import ThomasP from '../components/Thomas.vue';
import ZiH from '../components/Zi.vue';
export default {

  data() {
    return {
      selectedTab: '',
      ZiH,
      ThomasP, AmazighM, DebraiseM, RebeccaM
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

.content1 {
  margin-left: 220px;
  padding: 20px;
}
</style>



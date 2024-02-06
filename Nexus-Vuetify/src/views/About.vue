<template>
  <div class="about">

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
  import AmazighM from "../components/Devs/Amazigh.vue";
  import DebraiseM from "../components/Devs/Debraise.vue";
  import RebeccaM from "../components/Devs/Rebecca.vue";
  import ThomasP from '../components/Devs/Thomas.vue';
  import ZiH from '../components/Devs/Zi.vue';
  export default {

    data() {
      return {
        selectedTab: '',
        ZiH,
        ThomasP, AmazighM, DebraiseM, RebeccaM
      }
    }
  }
</script><style lang="scss">
.about {
  display: flex; /* Use flex layout for the .about container */
  flex-direction: column; /* Stack sidebar and content vertically */
  align-items: center; /* Center align children (sidebar) */
  
  .sidebar {
    width: 100%; 

    ul {
      list-style-type: none;
      padding: 0;
      margin: 0 auto; /* Center the list in the sidebar */
      display: flex; /* Use flexbox for horizontal layout */
      justify-content: space-between; /* Distribute space evenly between items */
      gap: 20px; /* Space between items */
      width: fit-content; /* Adjust width to fit its content */
      
      li {
        padding: 10px 20px; /* Increase padding for a more button-like appearance */
        cursor: pointer;
        border: 2px solid #ccc; /* Solid border for a button-like look */
        border-radius: 5px; /* Rounded corners */
        background-color: #eee; /* Light background color */
        transition: background-color 0.3s, border-color 0.3s; /* Smooth transition for hover effect */

        &:hover {
          background-color: #ddd; /* Darker background on hover */
          border-color: #999; /* Darker border on hover */
        }

        &:last-child {
          border-right: none;
        }
      }
    }
  }

  .content1 {
    margin-top: 20px;
    padding: 20px;
    width: 100%; /* Ensure content width matches sidebar */
  }
}
</style>







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
        <Zi/>
      </div>
      <div v-else-if="selectedTab === 'Rebecca'">
        <Rebecca/>
      </div>
      <div v-else-if="selectedTab === 'Thomas'">
        <Thomas/>
      </div>
      <div v-else-if="selectedTab === 'Debraise'">
        <Debraise/>
      </div>
      <div v-else-if="selectedTab === 'Amazigh'">
        <Amazigh/>
      </div>
    </div>

  </div>
</template>

<script scoped>
  import Amazigh from "../components/Devs/Amazigh.vue";
  import Debraise from "../components/Devs/Debraise.vue";
  import Rebecca from "../components/Devs/Rebecca.vue";
  import Thomas from '../components/Devs/Thomas.vue';
  import Zi from '../components/Devs/Zi.vue';
  export default {
    components:{
      Zi,
      Thomas, 
      Amazigh, 
      Debraise, 
      Rebecca
  },
    data() {
      return {
        selectedTab: '',
       
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
        margin-top: 20px;
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







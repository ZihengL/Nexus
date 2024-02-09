<template>
  <div class="about">

    <!-- Navbar -->
    <div class="navbar">
      <ul>
        <li :class="{ active: selectedTab === 'Zi' }" @click="selectedTab = 'Zi'">Zi Heng Liu</li>
        <li :class="{ active: selectedTab === 'Rebecca' }" @click="selectedTab = 'Rebecca'">Rebecca</li>
        <li :class="{ active: selectedTab === 'Thomas' }" @click="selectedTab = 'Thomas'">Thomas</li>
        <li :class="{ active: selectedTab === 'Debraise' }" @click="selectedTab = 'Debraise'">Debraise</li>
        <li :class="{ active: selectedTab === 'Amazigh' }" @click="selectedTab = 'Amazigh'">Amazigh</li>
      </ul>
    </div>

    <!-- Dynamic content based on navigation -->
    <div class="content">
      <div v-if="selectedTab === 'Zi'"><Zi/></div>
      <div v-else-if="selectedTab === 'Rebecca'"><Rebecca/></div>
      <div v-else-if="selectedTab === 'Thomas'"><Thomas/></div>
      <div v-else-if="selectedTab === 'Debraise'"><Debraise/></div>
      <div v-else-if="selectedTab === 'Amazigh'"><Amazigh/></div>
    </div>

  </div>
</template>

<script>
import Amazigh from "../components/Devs/Amazigh.vue";
import Debraise from "../components/Devs/Debraise.vue";
import Rebecca from "../components/Devs/Rebecca.vue";
import Thomas from '../components/Devs/Thomas.vue';
import Zi from '../components/Devs/Zi.vue';

export default {
  components: {
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
</script>

<style scoped lang="scss">
.about {
  display: flex;
  flex-direction: column; /* Keep the main direction as column */
  align-items: center; /* Center align the children */

  .navbar {
    width: 100%;
    ul {
      list-style-type: none;
      padding: 1%;
      margin: 0 auto; /* Center the list */
      display: flex; /* Use flexbox for a horizontal layout */
      justify-content: center; /* Center items horizontally */
      gap: 20px; /* Space between items */
      
      li {
        padding: 10px 0px;
        cursor: pointer;
        margin-left: 10% ;
        transition: background-color 0.3s, border-color 0.3s;

        &:hover {
          border-bottom: 2px solid black;
        }

        &.active {
          border-bottom: 2px solid black;
        }
      }
    }
  }

  .content {
    margin-top: 20px;
    padding: 20px;
    width: 100%; /* Ensure content width matches navbar */
  }
}
</style>

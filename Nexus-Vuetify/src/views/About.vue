<template>
  <div class="about">
    <!-- Navbar -->
    <div class="navbar">
      <ul id="liste">
        <input type="radio" name="liste" id="Team" v-model="selectedTab" value="Team" checked><label for="Team"
          class="glow team">Team</label>
        <li><input type="radio" name="liste" id="zi" v-model="selectedTab" value="Zi"><label for="zi" class="glow">Zi Heng
            Liu</label></li>
        <li><input type="radio" name="liste" id="Rebecca" v-model="selectedTab" value="Rebecca"><label for="Rebecca"
            class="glow">Rebecca</label></li>
        <li><input type="radio" name="liste" id="Thomas" v-model="selectedTab" value="Thomas"><label for="Thomas"
            class="glow">Thomas</label></li>
        <li><input type="radio" name="liste" id="Debraise" v-model="selectedTab" value="Debraise"><label for="Debraise"
            class="glow">Debraise</label></li>
        <li><input type="radio" name="liste" id="Amazigh" v-model="selectedTab" value="Amazigh"><label for="Amazigh"
            class="glow">Amazigh</label></li>
      </ul>
    </div>

    <!-- Dynamic content based on navigation -->
    <div class="content">
      <div v-if="selectedTab === 'Zi'" class="devs">
        <Zi />
      </div>
      <div v-else-if="selectedTab === 'Rebecca'" class="devs">
        <Rebecca />
      </div>
      <div v-else-if="selectedTab === 'Thomas'" class="devs">
        <Thomas />
      </div>
      <div v-else-if="selectedTab === 'Debraise'" class="devs">
        <Debraise />
      </div>
      <div v-else-if="selectedTab === 'Amazigh'" class="devs">
        <Amazigh />
      </div>
      <div v-else class="devs teamDes roundBorderSmall glass">
        <p>Nous sommes une équipe passionnée, dévouée à offrir des
          solutions innovantes et à relever les défis avec créativité. Composée d'individus talentueux et diversifiés,
          notre
          équipe est le moteur qui propulse notre entreprise vers l'excellence.</p>

        <p>Chaque membre de notre équipe apporte une expertise unique, allant de la technologie à la gestion de projet, en
          passant par le design et le marketing. Ensemble, nous formons une synergie dynamique, combinant nos forces pour
          fournir des résultats exceptionnels à nos clients.</p>

        <p>Notre approche collaborative et axée sur le client est au cœur de tout ce que nous faisons. Nous croyons
          fermement
          que la clé du succès réside dans la compréhension des besoins de nos clients et dans la fourniture de solutions
          sur mesure qui dépassent leurs attentes.</p>

        <p>En tant qu'équipe, nous sommes animés par la passion de l'innovation et de l'amélioration continue. Nous nous
          efforçons constamment d'apprendre, de grandir et d'évoluer, afin de rester à la pointe de notre industrie et de
          répondre aux demandes changeantes du marché.</p>

        <p>En tant qu'équipe, nous sommes animés par la passion de l'innovation et de l'amélioration continue. Nous nous
          Nous sommes fiers de notre héritage et de notre culture d'entreprise, qui favorise l'intégrité, la transparence
          et
          le respect mutuel. Ces valeurs guident chacune de nos actions et renforcent notre engagement envers l'excellence
          opérationnelle et le service à la clientèle.</p>

        <p>Nous sommes reconnaissants pour l'opportunité de travailler ensemble en tant qu'équipe, et nous sommes
          impatients
          de relever de nouveaux défis et de réaliser de grandes choses à l'avenir.</p>

        <p>Merci de faire partie de notre voyage.</p>

        <p>L'équipe de Nexus</p>
      </div>
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

<style lang="scss">
.about {
  display: flex;
  flex-direction: column;
  /* Keep the main direction as column */
  align-items: center;
  /* Center align the children */

  .navbar {
    width: 100%;
    text-align: center;

    .glow {
      color: var(--light-trans-2);
    }

    ul {
      list-style-type: none;
      display: inline-block;
      padding: 1%;
      margin: 0 auto;
      /* Center the list */
      display: flex;
      /* Use flexbox for a horizontal layout */
      justify-content: center;
      /* Center items horizontally */
      gap: 20px;
      /* Space between items */
      text-align: center;


      li {
        cursor: pointer;
        margin: 0% 6%;
        transition: background-color 0.3s, border-color 0.3s;

      }
    }

    input {
      display: none;
    }

    input:checked~label {
      color: var(--light);
      text-shadow:
        0 0 1.5px #ffffffa1,
        0 0 2.5px #ffffffa1,
        0 0 5px #ffffffa1,
        0 0 10px var(--marin-b),
        0 0 20px var(--marin-b),
        0 0 25px var(--marin-b),
        0 0 30px var(--marin-b),
        0 0 33px var(--marin-b);
      animation: neonGlow 0.5s ease-in-out infinite alternate;
    }

    .team {
      //font-size: 1.5rem;
    }
  }

  .content {
    margin-top: 1%;
    width: 100%;
    /* Ensure content width matches navbar */

    .devs {
      width: 70%;
      margin: auto;
    }

    .teamDes {
      margin-top: 1%;
      padding: 3% 2%;
      color: var(--light-trans);
    }
  }
}</style>

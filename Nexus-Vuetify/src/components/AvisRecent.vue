<template>
  <div class="glass Recent roundBorderSmall">
    <div class="absolute">
      <h2>Avis par les derniers sortie</h2>

      <ul>
        <li v-for="avis in avisRecents" :key="avis.id" class="glass roundBorderAll padding">
          <div class="containerStar">
            <div class="containerAvis">
              <div class="containerIMG">
                <img src="../assets/Dev_Picture/amazigh.png" alt="user" class="img" />
                <p>UserTOTO</p>
              </div>
              {{ avis.text }}
            </div>
            <div class="ratings">
              <v-rating hover :length="5" :size="32" :model-value="3" active-color="primary" class="rat" />
            </div>
          </div>
        </li>
      </ul>
      <div class="containerFormAvis">
        <input type="text" id="AvisId" placeholder="Votre Avis..." class="SaisieText glass roundBorderAll" />
        <div class="starContainer">
          <h3>Votre Évaluation:</h3>
          <div class="ratings">
            <v-rating hover :length="5" :size="32" :model-value="3" active-color="primary" class="rat" />
          </div>
        </div>
        <div class="fieldBtn">
          <div class="btn-layer"></div>

          <v-btn density="default" class="submit glow" @click="toggleProfile">
            Envoyer votre Avis
          </v-btn>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      avisRecents: [
        // Supposez que vous avez des données d'avis récents
        { id: 1, text: "Super produit!" },
        { id: 2, text: "Excellent service client!" },
        { id: 3, text: "Livraison rapide et efficace!" },
      ],
    };
  },
};
</script>

<style>
@import "../styles/settings.scss";

.Recent {
  flex-basis: 200%;
  padding: 20px;
  position: relative;

  .padding {
    padding: 10px;
  }

  h2 {
    color: var(--light);
    margin-bottom: 3%;

  }

  ul {
    list-style-type: none;


    li {
      width: 100%;
      padding: 2%;

      color: var(--light);
      margin-bottom: 20px;
      border: 1px solid var(--light);

      .containerStar {
        display: flex;
        align-items: center;
        justify-content: space-between;


        .containerAvis {
          display: flex;
          flex-direction: column;
          gap: 10px;
          align-items: flex-start;


          .containerIMG {
            display: flex;
            justify-content: center;
            gap: 10px;
            align-items: center;

          }
        }
      }

      .img {
        width: 40px;
        height: 40px;
        border-radius: 50%;

      }

    }
  }

  .containerFormAvis {
    margin-top: 15%;
  }

  .starContainer {
    margin-top: 2%;
    display: flex;
    align-items: center;
  }

  h3 {
    color: var(--light);
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

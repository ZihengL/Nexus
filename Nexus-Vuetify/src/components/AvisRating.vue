<template>
  <div class="glass ratingLast roundBorderSmall">
    <div class="absolute">
      <h2>Avis par les meilleurs notes</h2>
      <ul>
        <li v-for="avis in avisRecents" :key="avis.id" class="glass roundBorderSmall padding">
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
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const avisRecents = ref([
  { id: 1, text: "Super produit!" },
  { id: 2, text: "Excellent service client!" },
  { id: 3, text: "Livraison rapide et efficace!" },
]);
</script>

<style lang="scss">
@import "../styles/settings.scss";

.ratingLast {
  height: 100%;
  //flex-basis: 100%;
  padding: 20px;
  position: relative;



  h2 {
    color: var(--light);
    margin-bottom: 3%;

  }
  .padding {
    padding: 10px;
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

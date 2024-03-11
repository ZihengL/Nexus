<template>
  <div v-if="review" class="Recent roundBorderSmall">
    <div class="absolute" v-if="review.length > 0">
      <ul>
        <li v-for="(avis, index) in review" :key="index" class="glass roundBorderSmall padding">
          <div class="containerStar">
            <div class="containerAvis">
              <div class="containerIMG">
                <img :src="avis.profilePic || defaultPic" alt="Profile Picture" class="img" />
                <p>{{ avis.username }}</p>
                <!-- <p>UserTOTO {{ userName.value[index]?.username ?? 'Nom d\'utilisateur non disponible' }}</p> -->
              </div>
              {{ avis.comment }}
              <p>{{ avis.timestamp }}</p>
            </div>

            <div class="ratings">
              <v-rating readonly hover half-increments :length="5" :size="32" :model-value="avis.rating" active-color="primary" class="rat" />
            </div>
          </div>
        </li>
      </ul>

    </div>
  </div>
</template>

<script setup>
  import { defineProps, ref, onMounted, defineEmits } from "vue";
import { getOne, getReviewsAndUsernames } from '../../JS/fetchServices';
import storageManager from '../../JS/localStorageManager';
import defaultProfilePic from '../../assets/Dev_Picture/defaultProfilePic.png';
//import { forEach } from "core-js/core/array";

  const props = defineProps(["idGame", "sort"]);

  let review = ref(null);
  const defaultPic = ref(defaultProfilePic);

  onMounted(async () => {
    try {      
      let sorting = null;
      if(props.sort == "1"){
        sorting = { timestamp: false };
      }
      else if (props.sort == "2"){
        sorting = { timestamp: true };
      }
      else {
        sorting = { rating: false };
      }
      let reviewData = await getReviewsAndUsernames(props.idGame, sorting)
      //let reviewData = await getReviewsAndUsernames(props.idGame)
      review.value = reviewData;
      //console.log('review : ', review.value)
     

    } catch (error) {
      console.error("Error during component mounting:", error);
    }
  });
</script>

<style lang="scss">
//@import "../../styles/settings.scss";

.Recent {
  //flex-basis: 200%;
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
}
</style>

<template>
  <div v-if="review" class="AvisComp roundBorderSmall">
    <div class="absolute" v-if="review.length > 0">
      <h2 v-if="props.sort == '0'" >Les mieux notees</h2>
      <ul>
        <li v-for="(avis, index) in review" :key="index" class="glass roundBorderSmall padding">
          <div class="containerStar">
            <div class="containerAvis">
              <div class="containerIMG">
                <img :src="getImage(avis.users[0].picture) || defaultPic" alt="Profile Picture" class="img" />
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
  import { defineProps, ref, onMounted, defineEmits, watch } from "vue";
  import { getOne, getReviewsAndUsernames } from '../../JS/fetchServices';
  import storageManager from '../../JS/localStorageManager';
  import defaultProfilePic from '../../assets/Dev_Picture/defaultProfilePic.png';

  //const props = defineProps(["idGame", "sort", "nbMax", "taille"]);
  const props = defineProps({
    idGame: {
      type: Object,
      default: null,
    },
    sort: {
      type: String,
      default: "1",
    },
    nbMax: {
      type: Number,  // Correction ici
      default: 3,
    },
    taille: {
      type: Number,  // Correction ici
      default: 150,
    },
  });
let urlPic = null;
  let nb = props.taille;

  let review = ref(null);
  const defaultPic = ref(defaultProfilePic);

  const nbMax = props.nbMax;
  let nbPage = null;
  let paginationNb = 1;


  const setOverflow = (threshold) => {
    const avisComposant = document.querySelector(".AvisComp");


    if (avisComposant) {
      if (threshold < 4) {
        console.log(' yep');
        avisComposant.style.overflowX = 'hidden';
        avisComposant.style.overflowY = 'hidden';
      } else {
        console.log(' nope');
        avisComposant.style.overflowX = 'hidden';
        avisComposant.style.overflowY = "scroll";
      }
    } else {
      console.error("Element with class 'AvisComp' not found.");
    }
  };
  const getImage = (image) =>{
    console.log(' get pic,', image)
    urlPic = '/src/assets/Avatar/' + image;
    console.log('pic,', urlPic)
    return urlPic
  } 
  onMounted(async () => {
    try {
      review.value = props.idGame;
      console.log('review ,', review.value)

      // let sorting = null;
      // if(props.sort == "1"){
      //   sorting = { timestamp: false };
      // }
      // else if (props.sort == "2"){
      //   sorting = { timestamp: true };
      // }
      // else {
      //   sorting = { rating: false };
      // }
      // let reviewData = await getReviewsAndUsernames(props.idGame, sorting)
      // if(props.sort == "0"){
      //   review.value = reviewData.slice(0, props.nbMax);
      // }
      // else {
      //   review.value = reviewData;
      // }

      // if(review.value.length == 1){
      //   let nbHeight = nb;
      //   setOverflow(1);
      // }
      // else if(review.value.length == 2){
      //   let nbHeight = nb * 2;
      //   setOverflow(2);
      // }
      // else if(review.value.length == 3){
      //   let nbHeight = nb * 3;
      //   setOverflow(3);
      // }
      // else {
      //   let nbHeight = nb * 4;
      //   setOverflow(4);
      // }


    } catch (error) {
      console.error("Error during component mounting:", error);
    }
  });
  

</script>

<style lang="scss">
//@import "../../styles/settings.scss";

.AvisComp  {
  //position: relative;


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
      width: 99%;
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

<template>
  <div class="reviews_list">
    <h3>{{ title }}</h3>
    <template v-if="reviews_infos.reviews && reviews_infos.reviews.length > 0">
    
      <review
        v-for="review in reviews_infos.reviews"
        :key="review.id"
        :id="review.id"
        :img="review.img"
        :username="review.username"
        :comment="review.comment"
        :rating="review.rating"
        :userID="review.userID"
        :timestamp="review.timestamp"
      ></review>
    </template>
  
    <div v-else class="no_reviews">
      {{ reviews_infos.reviews_unavailable }}
    </div>
  </div>
</template>


<script setup>
import { onMounted, reactive } from "vue";
import review from "./game/reviewComponent.vue";
import {getReviewsAndUsernames } from '../JS/fetchServices';
// import PaginationComponent from "../components/PaginationComponent.vue";

const props = defineProps({
  sorting: {
    type: Object,
    default: null,
  },
  gameID: Number,
  title: String,
});

const reviews_infos = reactive({
  reviews: Array,
  reviews_unavailable : "Aucun avis pour le moment.",
});

onMounted(async () => {
  try {
    // console.log(" reviews_list props.sorting  : ", props.sorting);
    reviews_infos.reviews = await getReviewsAndUsernames(props.gameID, props.sorting)
    // console.log("reviews_list reviews : ", reviews_infos.reviews)
  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
</script>

<style scoped>
.reviews_list {
  display: flex;
  flex-direction: column;
  gap: 16px;

}
.no_reviews {
  text-align: center;
  padding: 5%;
  font-size: 200%;
  font-weight: bolder;
  color: white;
}
h3{
  color: white;
  text-decoration: underline;
}

</style>

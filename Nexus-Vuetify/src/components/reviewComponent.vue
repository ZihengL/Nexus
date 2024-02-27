<template>
  <div :id="props.id" class="review">
    <div class="review_header">
      <img :src="props.img || 'https://www.alleycat.org/wp-content/uploads/2019/03/FELV-cat.jpg'" :alt="props.username" class="review_avatar" />
      <div class="review_user_details">
        <h3 class="review_username">{{ props.username }}</h3>
      </div>
    </div>
    <div class="review_body">
      <p class="review_text">{{ props.comment }}</p>
    </div>
    <div class="review_footer">
      <v-rating
        :model-value="props.rating"
        :length="5"
        active-color="yellow"
        half-increments
        readonly
        size="14"
        color="white"
        empty-icon="mdi-star-outline"
      ></v-rating>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  id: Number,
  comment: String,
  img: String,
  rating: Number,
  username: String,
  userID: Number,
});

console.log("rating : ", props.rating);
</script>

<style scoped>
.review {
  border: 1px solid #eee;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 16px;
  display: flex;
  flex-direction: column;
  color: rgb(255, 255, 255);
}

.review_header {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}

.review_avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 8px;
}

.review_user_details {
  display: flex;
  flex-direction: column;
}

.review_username,
.review_userId {
  margin: 0;
}

.review_text {
  margin: 0 0 8px 0;
}

.review_footer {
  text-align: right;
}
</style>

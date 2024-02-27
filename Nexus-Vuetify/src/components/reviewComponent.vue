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
      <p class="review_timestamp">{{ props.timestamp }}</p>
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
  timestamp: String,
});

console.log("rating : ", props.rating);
console.log("comment : ", props.comment);
</script>

<style scoped>
.review {
  background-color: #2c3e50; /* Replace with the color from the image */
  color: white;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 16px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* if shadow is needed */
  display: flex;
  flex-direction: column;
}

.review_header {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.review_avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 2px solid white; /* if border is needed */
  margin-right: 16px;
}

.review_user_details {
  display: flex;
  flex-direction: column;
}

.review_username {
  font-size: 1.2em;
  font-weight: bold;
}

.review_text {
  margin-bottom: 16px;
  line-height: 1.4;
}

.review_timestamp {
  font-size: 0.8em;
  opacity: 0.7;
}

.review_footer {
  text-align: right;
}

.v-rating .v-icon {
  color: #f1c40f; /* active star color */
}

.v-rating .v-icon--empty {
  color: #7f8c8d; /* empty star color */
}
</style>

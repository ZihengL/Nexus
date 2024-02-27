<template>
  <div class="reviews_list">
    <review
      v-for="review in reviewInfo.infosToDisplay"
      :key="review.id"
      :id="review.id"
      :img="review.img"
      :username="review.username"
      :text="review.comment"
      :rating="review.rating"
      :userID="review.userID"
    ></review>
  </div>
</template>

<script setup>
import { onMounted, reactive } from "vue";
import review from "./reviewComponent.vue";
import { fetchData } from "../JS/fetch";

const reviewInfo = reactive({
  count: 0,
  necessaryProps: ["id", "rating", "username", "comment", "timestamp"],
  reviews: [],
  users: [],
  infosToDisplay: [],
});

const getReviews = async () => {
  const data = await fetchData(
    "reviews",
    "getAll",
    "gameID",
    "4",
    null,
    null,
    null,
    "GET"
  );

 
  if (data.length > 0) {
    reviewInfo.count = data.length;
    console.log("reviewInfo.count : ", reviewInfo.count);
    reviewInfo.reviews = [...data];
  }
  console.log("reviewInfo.reviews : ", reviewInfo.reviews);
};

const getUsers = async () => {
  if (reviewInfo.count > 0) {
    for (let review of reviewInfo.reviews) {
      const user = await fetchData(
        "users",
        "getOne",
        "id",
        review.userID,
        null,
        null,
        null,
        "GET"
      );
      reviewInfo.users.push(user);
    }
    console.log("reviewInfo.users : ", reviewInfo.users);
  }
};

const formInfoToDisplay = () => {
  reviewInfo.infosToDisplay = reviewInfo.reviews.map((review) => {
    const user = reviewInfo.users.find((user) => user.id === review.userID);
    return {
      ...review,
      username: user ? user.username : "Unknown",
    };
  });
  console.log("reviewInfo.infosToDisplay : ", reviewInfo.infosToDisplay);
};

onMounted(async () => {
  try {
    await getReviews();
    if (reviewInfo.count > 0) {
      await getUsers();
      if (reviewInfo.users.length > 0) {
        formInfoToDisplay();
      }
    } else {
      console.log("No reviews found, skipping user fetching.");
    }
  } catch (error) {
    console.error("Error during component mounting:", error);
  }
});
</script>

<style scoped>
.reviews_list {
  display: flex;
  flex-direction: column;
  gap: 16px; /* Adjust spacing between reviews as needed */
}
</style>

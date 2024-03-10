<template>
  <div class="containerFormAvis">
    <input
      type="text"
      id="AvisId"
      placeholder="Votre Avis..."
      :model-value="state.comment"
      class="SaisieText glass roundBorderSmall"
    />
    <div class="starContainer">
      <h3>Votre Évaluation:</h3>
      <div class="ratings">
        <v-rating
          hover
          :length="5"
          :size="32"
          :model-value="state.ratingValue"
          active-color="primary"
          class="rat"
        />
      </div>
    </div>
    <btnComp :contenu="'Envoyer votre Avis'" @toggle-btn="toggleProfile" />
  </div>
</template>
<script setup>
import btnComp from "../btnComponent.vue";
import { create, getOne, deleteData } from "../../JS/fetchServices";
import { reactive, onMounted, watch, defineProps } from "vue";

const props = defineProps({
  gameID: {
    type: Number,
  },
  userID: {
    type: Number,
    default: 9,
  },
});

const state = reactive({
  ratingValue: 0,
  timestamp: new Date().toISOString().split("T")[0],
  comment:"",
  errorMessage: "",
});

const validateData = async () => {
  // Assuming getOne() checks if a user exists, which doesn't directly relate to validating the input data
  // So, this part might need adjustment based on actual requirement
  let userExist = await getOne("users", "id", props.userID);
  if (!userExist) {
    state.errorMessage = "User does not exist.";
    return false;
  }
  if (state.ratingValue <= 0) {
    state.errorMessage = "Rating must be higher than 0.";
    return false;
  }
  if (!state.comment.trim()) {
    state.errorMessage = "Comment cannot be empty.";
    return false;
  }
  // Reset error message if validation passes
  state.errorMessage = "";
  return true;
};

const createReview = async () => {
  const isValid = await validateData(); // Ensure validation is awaited and checked
  if (isValid) {
    try {
      let reviewIsCreated = await create("reviews", {
        userID: props.userID,
        gameID: props.gameID,
        rating: state.ratingValue,
        comment: state.comment,
      });
      // Handle success (e.g., clear form, show success message)
    } catch (error) {
      // Handle error (e.g., show error message)
      console.error("Failed to create review:", error);
    }
  } else {
    console.log(state.errorMessage); // Log or handle validation error message
  }
};

</script>
<style lang="scss">
.containerFormAvis {
  margin-top: 5%;

  .starContainer {
    margin-top: 2%;
    display: flex;
    align-items: center;
    justify-content: center;
    h3 {
      color: var(--light);
    }
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

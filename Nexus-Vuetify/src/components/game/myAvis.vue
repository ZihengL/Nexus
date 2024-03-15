<template>
  <div class="containerFormAvis">
    <input type="text" id="AvisId" placeholder="Votre Avis..." v-model="state.comment"
      class="SaisieText glass roundBorderSmall" />
    <div class="starContainer">
      <h3>Votre Évaluation:</h3>
      <div class="ratings">
        <v-rating hover :length="5" :size="32" v-model="state.ratingValue" active-color="primary" class="rat"
          half-increments />
      </div>
    </div>
    <btnComp :contenu="'Envoyer votre Avis'" @toggle-btn="createReview" />
  </div>
</template>
<script setup>
import btnComp from "../btnComponent.vue";
import { getOne, actionWithValidation } from "../../JS/fetchServices";
import storageManager from "../../JS/localStorageManager";
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
  comment: "",
  errorMessage: "",
});

const validateData = async () => {
  console.log("storageManager.getIdDev() : ", storageManager.getIdDev());
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
  state.errorMessage = "";
  return true;
};

const createReview = async () => {
  const isValid = await validateData();
  console.log("isValid : ", isValid);
  let userid = storageManager.getIdDev();
  console.log("props.gameID : ", props.gameID);

  if (isValid) {
    if (userid && storageManager.getAccessToken()) {
      try {
        let data = {
          userID: userid,
          gameID: props.gameID,
          rating: state.ratingValue,
          comment: state.comment,
        }
        console.log("REVIEW ", data)
        let reviewIsCreated = await actionWithValidation("reviews", "create", data);
        if (reviewIsCreated == true) {
          window.location.reload();
        }
        console.log("reviewIsCreated : ", reviewIsCreated);
      } catch (error) {
        console.error("Failed to create review:", error);
      }
    }
  } else {
    console.log(state.errorMessage);
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

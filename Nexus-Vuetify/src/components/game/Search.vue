<template>
  <div id="search">
    <head>
      <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        rel="stylesheet"
      />
    </head>
    <div class="search-container roundBorderSmall glass">
      <input
        type="text"
        :value="props.modelValue"
        @input="onInput"
        @keyup.enter="props.onSearch(props.modelValue)"
        class="search-input"
        :placeholder="props.placeholder_title"
      />
      <button class="search-button">
        <i @click="() => props.onSearch(props.modelValue)" class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </div>
</template>
<script setup>
import { reactive, defineProps } from "vue";
const emits = defineEmits(["update:modelValue"]);

const props = defineProps({
  placeholder_title: {
    type: String,
    default: "Trouver un jeu...",
  },
  modelValue: String,
  onSearch: Function,
});

const onInput = (event) => {
  emits("update:modelValue", event.target.value);
  // console.log(event.target.value);
};


</script>

<style lang="scss" scoped>
#search {
  .search-container {
    margin-top: 5%;
    margin-left: 67%;
    position: relative;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    //background: #fdfdff; /* Light background */
    //border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    height: 40px;
  }

  .search-input {
    padding: 10px 10px 10px 10px; /* Uniform padding */
    font-size: 16px;
    border: none;
    outline: none;
    color: #000000; /* Black text */
    background: transparent; /* Inherit from container */
    width: 100%; /* Full width to fill the container */
  }

  .search-button {
    position: absolute;
    right: 0; /* Align to the right */
    border: none;
    background: none;
    cursor: pointer;
    color: var(--light-trans-2);
    margin-right: 2%;
  }
}
</style>

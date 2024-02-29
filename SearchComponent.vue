<template>
  <div id="search">
    <head>
      <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        rel="stylesheet"
      />
    </head>
    <div :class="props.inputClass">
      <input
        @input="updateValue($event.target.value)"
        type="text"
        :value="props.modelValue"
        :placeholder="props.placeholder"
      />
      <button :class="props.loopClass">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, defineEmits } from "vue";

const props = defineProps({
  placeholder: String,
  modelValue: String,
  inputClass: {
    type: String,
    default: "search-container roundBorderSmall glass",
  },
  loopClass: {
    type: String,
    default: "search-button",
  },
});

const emit = defineEmits({
  "update:modelValue": null,
  // "update:": (payload) => typeof payload === "string",
  // 'itemSelected': payload => typeof payload === 'object', // Assuming payload is an object
  // 'selectionCleared': null // No payload expected
});

function updateValue(value) {
  emit("update:modelValue", value);
  console.log("search text  : ", value);
}

const search_data = reactive({});

onMounted(async () => {});
</script>

<style lang="scss" scoped >
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

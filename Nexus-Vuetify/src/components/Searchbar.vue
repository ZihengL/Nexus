<template>
  <v-card class="transparent-background pa-4">
    <v-text-field
      v-model="searchQuery"
      append-icon="mdi-magnify"
      label="Search Games"
      single-line
      hide-details
    ></v-text-field>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
    };
  },
  watch: {
    searchQuery(newValue) {
      // Implement your search logic here
      console.log('Searching for:', newValue);
    },
  },
};
</script>

<style scoped>
.transparent-background {
  background-color: transparent;
}

.transparent-background {
  box-shadow: none; /* Removes shadow */
  border: none; /* Removes border */
}
</style>

<template>
  <div class="container">
    <AvisRecent />
    <AvisRating />

  </div>
</template>
<script>
import AvisRating from './AvisRating.vue';
import AvisRecent from './AvisRecent';


export default {
  components: {
    AvisRating,
    AvisRecent
  }
}

</script>
<style scoped>

.container {
  display: flex;
  flex-direction: row;
  margin-top: 15px;
  text-align: left;
}
</style>

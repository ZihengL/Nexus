<template>
  <div class="test_view">
    <!-- <Homecarrousel /> -->
    <!-- <Info style="margin: 2% auto" /> -->
    TEST
  </div>
</template>

<script scoped>
// import { forEach } from 'core-js/core/array'
import { fetchData } from '../JS/fetch'
//import Card from '../components/Card.vue';

export default {
  components: {

  },
  mounted() {
    const filters = {
      ratingAverage: { gt: 1, lte: 7 },
    };
    const sorting = {
      ratingAverage: true,
    };

    const includedColumns = ["id", "developerID", "title"];

    const create = {
      email: "d",
      name: "Katty",
      password: "1",
    };

    const login = {
      email: "d",
      password: "1",
    };

    const jsonBody = { filters, sorting, includedColumns };
    const registerBody = { create };
    const loginBody = { login };

    // fetchData('users', 'create', "null", null, registerBody, 'POST')
    // fetchData('games', 'getAllMatching', null, null, jsonBody, 'POST')
    fetchData("users", "login", null, null, loginBody, "POST")
      // .then(data => {
      //   this.carouselItems = data.map((item, index) => ({ ...item, image: `./src/assets/image/img${index + 1}.png` }));

      //   console.log('data : ', this.carouselItems);
      //   // Now you can access carouselItems with the added 'image' property

      //   // Add the following line to initiate the automatic sliding
      //   this.runNextAuto = setTimeout(() => {
      //     this.showSlider('next');
      //   }, this.timeAutoNext);
      // })
      .catch((error) => {
        // Handle errors if any
        console.error("Error fetching data:", error);
      });
  },
};
</script>

<style lang="scss" scoped>
.test_view {
  text-align: center;
  font-size: 40;
  color: aliceblue;
}
</style>

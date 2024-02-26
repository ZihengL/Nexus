export default {
  data() {
    return {
      carouselItems: [],
      timeRunning: 3000,
      timeAutoNext: 5000,
      runTimeOut: null,
      runNextAuto: null,
    };
  },
  methods: {
    showSlider(type) {
      if (type === "next") {
        this.carouselItems.push(this.carouselItems.shift());
      } else {
        this.carouselItems.unshift(this.carouselItems.pop());
      }

      clearTimeout(this.runTimeOut);
      this.runTimeOut = setTimeout(() => {
        // Reset animation classes
        this.$el.classList.remove("next");
        this.$el.classList.remove("prev");
      }, this.timeRunning);

      clearTimeout(this.runNextAuto);
      this.runNextAuto = setTimeout(() => {
        this.showSlider("next");
      }, this.timeAutoNext);
    },
  },
  mounted() {
    const filters = {
      ratingAverage: { gt: 1, lte: 7 },
    };
    const sorting = {
      ratingAverage: true,
    };

    const includedColumns = ["id", "developerID", "title"];

    const jsonBody = { filters, sorting, includedColumns };

    fetchData("games", "getAllMatching", null, null, jsonBody, "POST")
      .then((data) => {
        // Take only the first 4 items
        this.carouselItems = data
          .slice(0, 4)
          .map((item, index) => ({
            ...item,
            image: `https://firebasestorage.googleapis.com/v0/b/nexus-414517.appspot.com/o/Imagejeux%2FMario.png?alt=media&token=c94515c3-03a7-4bf6-8344-80b00012b051`,
          }));

        console.log("data : ", this.carouselItems);
        // Now you can access carouselItems with the added 'image' property

        // Add the following line to initiate the automatic sliding
        this.runNextAuto = setTimeout(() => {
          this.showSlider("next");
        }, this.timeAutoNext);
      })
      .catch((error) => {
        // Handle errors if any
        console.error("Error fetching data:", error);
      });
  },
};

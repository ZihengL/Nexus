// Firebase and fetchData imports
import { getDownloadURL, ref } from "firebase/storage";
import { fetchData } from "../JS/fetch";
import { storage } from "../JS/firebaseconfig";

export default {
  name: "GameCarousel",
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
        this.$el.classList.remove("next", "prev");
      }, this.timeRunning);
      clearTimeout(this.runNextAuto);
      this.runNextAuto = setTimeout(() => {
        this.showSlider("next");
      }, this.timeAutoNext);
    },
    fetchGameImages(games) {
      const imageFetchPromises = games.map((game) => {
        const files = game.files || `defaultName.jpg`;
        const imagePath = `GameTitle/${files}`;
        const imageRef = ref(storage, imagePath);

        return getDownloadURL(imageRef)
          .then((url) => {
            return { ...game, image: url };
          })
          .catch((error) => {
            console.error(
              `Error fetching image for ${game.title} with image ${files}:`,
              error
            );
            return { ...game, image: "path/to/default/image.jpg" }; // Fallback image
          });
      });
      return Promise.all(imageFetchPromises);
    },
  },
  mounted() {
    const filters = { ratingAverage: { gt: 1, lte: 7 } };
    const sorting = { ratingAverage: true };
    const includedColumns = ["id", "developerID", "title", "files"];

    const jsonBody = { filters, sorting, includedColumns };

    fetchData("games", "getAllMatching", null, null, null, null,jsonBody, "POST")
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error("Fetched data is not an array");
        }
        return this.fetchGameImages(data.slice(0, 4));
      })
      .then((carouselItemsWithImages) => {
        this.carouselItems = carouselItemsWithImages;
        this.runNextAuto = setTimeout(() => {
          this.showSlider("next");
        }, this.timeAutoNext);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  },
};

import { getAllMatching } from "./fetchServices.js";
import { getStorage, ref, getDownloadURL } from "firebase/storage";

const storage = getStorage();

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
    // Simplified slider logic for readability
    showSlider(direction) {
      if (direction === "next") {
        this.carouselItems.push(this.carouselItems.shift());
      } else {
        this.carouselItems.unshift(this.carouselItems.pop());
      }
      this.resetSlider();
    },
    resetSlider() {
      clearTimeout(this.runTimeOut);
      this.runTimeOut = setTimeout(() => {
        this.$el.classList.remove("next", "prev");
      }, this.timeRunning);

      clearTimeout(this.runNextAuto);
      this.setNextAuto();
    },
    setNextAuto() {
      this.runNextAuto = setTimeout(() => {
        this.showSlider("next");
      }, this.timeAutoNext);
    },
    async fetchGameImages(games) {
      try {
        const imageFetchPromises = games.map(async (game) => {
          const files = game.id || `defaultName.jpg`;
          const imagePath = `Games/${files}/media/${files}_0.png`;
          
          //console.log('imagePath : ', imagePath);
          const imageRef = ref(storage, imagePath);
          //console.log('imageRef : ', imageRef);

          try {
            const url = await getDownloadURL(imageRef);
            return { ...game, image: url };
          } catch (error) {
            console.error(`Error fetching image for ${game.title}:`, error);
            return { ...game, image: "../assets/imgJeuxLogo/Mario.png" }; // Fallback image
          }
        });

        return Promise.all(imageFetchPromises);
      } catch (error) {
        console.error("Error fetching game images:", error);
        return []; // Return an empty array in case of error
      }
    },
  },
  async mounted() {
    try {
      const filters = { ratingAverage: { gt: 1, lte: 7 } };
      const sorting = { id: true };
      const includedColumns = ["id", "developerID", "title", "files"];

      // const jsonBody = { filters, sorting, includedColumns };

      getAllMatching("games", filters, includedColumns, sorting)
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error("Fetched data is not an array");
        }
        //console.log('data : ' , data)
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
    } catch (error) {
      console.error("Error fetching game images:", error);
      //return []; // Return an empty array in case of error
    }
  
  }
}
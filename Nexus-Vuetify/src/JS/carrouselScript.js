import { fetchData } from "./fetch.js";
import { getStorage, ref, listAll, getDownloadURL } from "firebase/storage";

const storage = getStorage();

// Assuming 'GameTitle/' is the directory where your image is stored
// and you're looking to list all files within this directory
const listRef = ref(storage, 'GameTitle/');

// Find all the prefixes and items under 'GameTitle/'
listAll(listRef)
  .then((res) => {


   console.log( "firebase:"+res)
    res.items.forEach((itemRef) => {
      // For each item found, assuming these are your images, get the download URL
      getDownloadURL(itemRef).then((url) => {
        // Here, you have the URL of each image, and you can use it as needed
        // For example, setting it as the src attribute of an <img> element:
        console.log("Image URL:", url); // Log the URL or handle it as needed
        // Example: document.querySelector('img').src = url;
      }).catch((error) => {
        // Handle any errors in getting the download URL
        console.error("Error getting image download URL", error);
      });
    });
  }).catch((error) => {
    // Handle any errors in listing the files
    console.error("Error listing files:", error);
  });


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
          const gameId= game.id
          const files = game.title || `defaultName.jpg`;
          const imagePath = `Games/${gameId}/`;
          const imageRef = ref(storage, imagePath);

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
      const sorting = { ratingAverage: true };
      const includedColumns = ["id", "developerID", "title", "files"];

      const jsonBody = { filters, sorting, includedColumns };

    fetchData("games", "getAllMatching", null, null, null, null,jsonBody, "POST")
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error("Fetched data is not an array");
        }
        console.log('data : ' , data)
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


// Firebase and fetchData imports
import { storage } from '../JS/firebaseconfig';
import { ref, getDownloadURL } from 'firebase/storage';
import { fetchData } from '../JS/fetch';

export default {
  name: 'GameCarousel',
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
      if (type === 'next') {
        this.carouselItems.push(this.carouselItems.shift());
      } else {
        this.carouselItems.unshift(this.carouselItems.pop());
      }
      clearTimeout(this.runTimeOut);
      this.runTimeOut = setTimeout(() => {
        this.$el.classList.remove('next', 'prev');
      }, this.timeRunning);
      clearTimeout(this.runNextAuto);
      this.runNextAuto = setTimeout(() => {
        this.showSlider('next');
      }, this.timeAutoNext);
    },
    fetchGameImages(games) {
      const imageFetchPromises = games.map((game) => {
        const imageName = game.imageName || `defaultName.jpg`;
        const imagePath = `GameTitle/${imageName}`;
        const imageRef = ref(storage, imagePath);

        return getDownloadURL(imageRef)
          .then((url) => {
            return { ...game, image: url };
          })
          .catch((error) => {
            console.error(`Error fetching image for ${game.title} with image ${imageName}:`, error);
            return { ...game, image: 'path/to/default/image.jpg' }; // Fallback image
          });
      });
      return Promise.all(imageFetchPromises);
    },
  },
  mounted() {
    const filters = { ratingAverage: { gt: 1, lte: 7 } };
    const sorting = { ratingAverage: true };
    const includedColumns = ['id', 'developerID', 'title', 'imageName'];

    const jsonBody = { filters, sorting, includedColumns };

    fetchData('games', 'getAllMatching', null, null, jsonBody, 'POST')
      .then((data) => {
        if (!Array.isArray(data)) {
          throw new Error('Fetched data is not an array');
        }
        return this.fetchGameImages(data.slice(0, 4));
      })
      .then((carouselItemsWithImages) => {
        this.carouselItems = carouselItemsWithImages;
        this.runNextAuto = setTimeout(() => {
          this.showSlider('next');
        }, this.timeAutoNext);
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  },
};

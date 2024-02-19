// import { forEach } from 'core-js/core/array'
import { fetchData } from '../JS/fetch'

export default {
  data () {
    return {
      carouselItems: [],
      timeRunning: 3000,
      timeAutoNext: 5000,
      runTimeOut: null,
      runNextAuto: null
    }
  },
  methods: {
    showSlider (type) {
      if (type === 'next') {
        this.carouselItems.push(this.carouselItems.shift())
      } else {
        this.carouselItems.unshift(this.carouselItems.pop())
      }

      clearTimeout(this.runTimeOut)
      this.runTimeOut = setTimeout(() => {
        // Reset animation classes
        this.$el.classList.remove('next')
        this.$el.classList.remove('prev')
      }, this.timeRunning)

      clearTimeout(this.runNextAuto)
      this.runNextAuto = setTimeout(() => {
        this.showSlider('next')
      }, this.timeAutoNext)
    }
  },
  mounted () {

    const filters = {
      ratingAverage: { gt: 1, lte: 7 },
    }
    const sorting = {
      ratingAverage: true
    }

    const includedColumns = ['id', 'developerID', 'title']

    const create = {
      email : "k@k",
      name: 'Katty', 
      password : "1",
    }


    const jsonBody = { filters, sorting, includedColumns }
    const loginBody = { create }

    fetchData('users', 'create', "user", null, loginBody, 'POST')
    // fetchData('games', 'getAllMatching', null, null, jsonBody, 'POST')

    
    // .then(data => {
    //   this.carouselItems = data.map((item, index) => ({ ...item, image: `./src/assets/image/img${index + 1}.png` }));

    //   console.log('data : ', this.carouselItems);
    //   // Now you can access carouselItems with the added 'image' property

    //   // Add the following line to initiate the automatic sliding
    //   this.runNextAuto = setTimeout(() => {
    //     this.showSlider('next');
    //   }, this.timeAutoNext);
    // })
    .catch(error => {
      // Handle errors if any
      console.error('Error fetching data:', error);
    });
  }
}

import { fetchData } from '../JS/fetch'

export default {
  data () {
    return {
      carouselItems: [
        {
          image: './src/assets/image/img1.png',
          author: 'LUNDEV',
          title: 'DESIGN SLIDER',
          topic: 'ANIMAL',
          description:
            'Lorem ipsum dolor, sit amet consectetur adipisicing elit...',
          thumbnailTitle: 'Name Slider',
          thumbnailDescription: 'Description'
        },
        {
          image: './src/assets/image/img2.png',
          author: 'LUNDEV',
          title: 'DESIGN SLIDER',
          topic: 'ANIMAL',
          description:
            'Lorem ipsum dolor, sit amet consectetur adipisicing elit...',
          thumbnailTitle: 'Name Slider',
          thumbnailDescription: 'Description'
        },
        {
          image: './src/assets/image/img3.png',
          author: 'LUNDEV',
          title: 'DESIGN SLIDER',
          topic: 'ANIMAL',
          description:
            'Lorem ipsum dolor, sit amet consectetur adipisicing elit...',
          thumbnailTitle: 'Name Slider',
          thumbnailDescription: 'Description'
        },
        {
          image: './src/assets/image/img4.png',
          author: 'LUNDEV',
          title: 'DESIGN SLIDER',
          topic: 'ANIMAL',
          description:
            'Lorem ipsum dolor, sit amet consectetur adipisicing elit...',
          thumbnailTitle: 'Name Slider',
          thumbnailDescription: 'Description'
        }
        // Add more items as needed
      ],
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
        // value LIKE 
  	  // name: { contain: 'super' },
        // exact value
      title: 'Super Cat', 
  	  // developperID: '4',
        // only one range
      // ratingAverage: { gt: 1},
    }

    const sorting = {
      releaseDate: false
    }

    const includedColumns = ['id', 'developerID']

    const jsonBody = { filters, sorting, includedColumns }

    fetchData('games', 'applyFiltersAndSorting', null, null, jsonBody, 'POST')
    // fetchData("games", "getBy", "Id", "2", null, "GET");
    // fetchData("games", "getAll", "GET");
    
  }
}

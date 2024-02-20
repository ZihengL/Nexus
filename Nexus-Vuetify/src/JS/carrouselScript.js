

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
  
}

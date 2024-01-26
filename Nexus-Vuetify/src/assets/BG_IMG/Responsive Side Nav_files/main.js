import { createApp } from '/node_modules/.vite/vue.js?v=e84f2cf5'
import App from '/src/App.vue'
import router from '/src/router/index.js'

createApp(App).use(router).mount('#app')

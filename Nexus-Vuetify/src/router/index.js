// Composables
import { createRouter, createWebHistory } from 'vue-router'
import 'vuetify/dist/vuetify.css';


const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/Default.vue'),
    children: [
      {
        path: '/',
        name: 'Home',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/Home.vue'),
      },
      {
        path: '/Store',
        name: 'Store',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/StoreView.vue'),
      },
      {
        path: '/Profile/:IdDev',
        name: 'Profile',
        component: () => import('@/views/FullProfilePage.vue'),
        props: true,
      },
      {
        path: '/About',
        name: 'About',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/About.vue'),
      },
      {
        path: '/Game/:idGame',
        name: 'Game',
        component: () => import('@/views/GameVue.vue'),
        props: true, 
        /*props: (route) => ({
          idGame: route.params.idGame,
          // Ajoutez votre deuxième prop ici
          urlImg: route.params.urlImg, // Remplacez 'Valeur par défaut' par la valeur souhaitée ou calculez-la dynamiquement
        }),*/
      },
      
      {
        path: '/Dev/:idDevl',
        name: 'Dev',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/profileDev.vue'),
        props: true, 
      },
      {
        path: '/upload',
        name: 'upload',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/UploadGameView.vue'),
        props: true, 
      },
      {
        path: '/Login/',
        name: 'Login',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/Login-Profile.vue'),
      },
      {
        path: '/test',
        name: 'test',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/testView.vue'),
      },
      {
        path: '/upload',
        name: 'upload',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/UploadGameView.vue'),
      },
      {
        path: '/update/:gameToUpdateId',
        name: 'update',
        // route level code-splitting
        // this generates a separate chunk (Home-[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () => import('@/views/UpdateGameView.vue'),
      },
    //   {
    //     path: '/success',
    //     name: 'Stripesuccess',
    //     component: SuccessPage,
    //     props: (route) => ({ sessionId: route.query.session_id })
    // },
    // {
    //     path: '/cancel',
    //     name: 'Stripecancel',
    //     component: () => import('@/views')
    // }
    ],
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
})

export default router

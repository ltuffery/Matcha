import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import MainView from '../views/MainView.vue'
import VerifyEmailView from '../views/VerifyEmailView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      beforeEnter: () => {
        console.log(RegExp("token=[^;]+").exec(document.cookie)[0])
      }
    },
    {
      path: '/main',
      name: 'main',
      component: MainView,
    },
    {
      path: '/verify',
      name: 'verify',
      component: VerifyEmailView,
    }
  ],
})

export default router

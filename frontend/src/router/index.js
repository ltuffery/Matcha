import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import MainView from '../views/MainView.vue'
import VerifyEmailView from '../views/VerifyEmailView.vue'
import NewPasswordView from '../views/NewPasswordView.vue'
import { authGuard } from '@/middlewares/auth'
import { isAuthenticated } from '@/services/auth'
import SearchUsersView from '@/views/SearchUsersView.vue'
import ChatView from '@/views/ChatView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      beforeEnter: (to, from, next) => {
        isAuthenticated().then(value => {
          if (value) {
            next({ name: 'main' })
          } else {
            next()
          }
        })
      },
    },
    {
      path: '/main',
      name: 'main',
      component: MainView,
      beforeEnter: [authGuard],
    },
    {
      path: '/search',
      name: 'search',
      component: SearchUsersView,
      beforeEnter: [authGuard],
    },
    {
      path: '/verify',
      name: 'verify',
      component: VerifyEmailView,
    },
    {
      path: '/forgot',
      name: 'forgot',
      component: NewPasswordView,
    },
    {
      path: '/chat',
      name: 'chat',
      component: ChatView,
    },
  ],
})

export default router

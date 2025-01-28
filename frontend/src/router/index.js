import HistoryView from '@/views/HistoryView.vue'
import ChatView from '@/views/ChatView.vue'
import ConvView from '@/views/ConvView.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { isAuthenticated } from '@/services/auth.js'
import HomeView from '@/views/HomeView.vue'
import MainView from '@/views/MainView.vue'
import { authGuard } from '@/middlewares/auth.js'
import SearchUsersView from '@/views/SearchUsersView.vue'
import SettingsView from '@/views/SettingsView.vue'
import VerifyEmailView from '@/views/VerifyEmailView.vue'
import NewPasswordView from '@/views/NewPasswordView.vue'
import ProfileView from "@/views/UserProfile.vue";

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
      path: '/settings',
      name: 'settings',
      component: SettingsView,
      beforeEnter: [authGuard],
    },
    {
      path: '/history',
      name: 'history',
      component: HistoryView,
      beforeEnter: [authGuard],
    },
    {
      path: '/chat',
      name: 'chat',
      component: ChatView,
      beforeEnter: [authGuard],
    },
    {
      path: '/chat/:username',
      name: 'conversation',
      component: ConvView,
      beforeEnter: [authGuard],
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView,
      // beforeEnter: [authGuard],
    },
  ],
})

export default router

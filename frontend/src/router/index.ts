import HistoryView from '@/views/HistoryView.vue'
import ChatView from '@/views/ChatView.vue'
import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { isAuthenticated } from '@/services/auth'
import AuthView from '@/views/AuthView.vue'
import { authGuard } from '@/middlewares/auth'
import SearchUsersView from '@/views/SearchUsersView.vue'
import SettingsView from '@/views/SettingsView.vue'
import VerifyEmailView from '@/views/VerifyEmailView.vue'
import NewPasswordView from '@/views/NewPasswordView.vue'
import UserProfileView from '@/views/UserProfileView.vue'
import NotificationsView from '@/views/NotificationsView.vue'
import NotFound from '@/views/NotFound.vue'
import EditProfileView from '@/views/EditProfileView.vue'
import HomeView from '@/views/HomeView.vue'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'auth',
    component: AuthView,
    beforeEnter: (to, from, next) => {
      isAuthenticated().then(value => {
        if (value) {
          next({ name: 'home' })
        } else {
          next()
        }
      })
    },
  },
  {
    path: '/home',
    name: 'home',
    component: HomeView,
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
    path: '/matches',
    name: 'matches',
    component: HistoryView,
    beforeEnter: [authGuard],
  },
  {
    path: '/messages',
    name: 'messages',
    component: ChatView,
    beforeEnter: [authGuard],
  },
  {
    path: '/messages/:username',
    name: 'messages.user',
    component: ChatView,
    beforeEnter: [authGuard],
  },
  {
    path: '/profile/:username',
    name: 'profile',
    component: UserProfileView,
    beforeEnter: [authGuard],
  },
  {
    path: '/notifications',
    name: 'notifications',
    component: NotificationsView,
    beforeEnter: [authGuard],
  },
  {
    path: '/profile/:username/edit',
    name: 'profile.edit',
    component: EditProfileView,
    beforeEnter: [authGuard],
  },
  {
    path: '/:pathMatch(.*)*',
    name: '404',
    component: NotFound,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router

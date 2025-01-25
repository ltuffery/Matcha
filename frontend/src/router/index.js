import HistoryView from "@/views/HistoryView.vue";
import ChatView from '@/views/ChatView.vue'
import ConvView from '@/views/ConvView.vue'

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
      path: '/history',
      name: 'history',
      component: HistoryView,
      beforeEnter: [authGuard],
    },
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
  ],
})

export default router

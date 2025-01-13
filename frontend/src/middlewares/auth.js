import { isAuthenticated } from '@/services/auth'

export const authGuard = (to, from, next) => {
  isAuthenticated().then(value => {
    if (value) {
      next()
    } else {
      next({ name: 'home' })
    }
  })
}

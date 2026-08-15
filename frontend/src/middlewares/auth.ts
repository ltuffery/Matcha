import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'
import { isAuthenticated } from '@/services/auth'

export const authGuard = (
  _to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext,
) => {
  isAuthenticated().then(value => {
    if (value) {
      next()
    } else {
      next({ name: 'home' })
    }
  })
}

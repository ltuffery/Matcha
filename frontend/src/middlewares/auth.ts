import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'
import { useAuth } from '@/composable/useAuth'

export const authGuard = (
  _to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext,
) => {
  useAuth().isAuthenticated().then(value => {
    if (value) {
      next()
    } else {
      next({ name: 'auth' })
    }
  })
}

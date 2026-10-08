import { ref } from 'vue'
import { authApi, type LoginResponse, type RegisterCredentials } from '@/api/auth'
import { getSocket } from '@/plugins/socket'
import router from '@/router'
import { useAuthStore } from '@/store/useAuthStore'

export interface JwtPayload {
  username: string
  exp: number
}

export function useAuth() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const login = async (
    username: string,
    password: string,
  ): Promise<LoginResponse> => {
    isLoading.value = true

    const res = await authApi.login({
      username: username,
      password: password
    })

    if (!res.success) {
      error.value = res.error as string
    } else {
      localStorage.setItem('jwt', res.token as string)
      localStorage.setItem('refresh', res.refresh as string)

      await useAuthStore().fetchMe()
    }

    isLoading.value = false

    return res
  }

  const register = async (data: FormData) => {
    const res = await authApi.register(data)

    if (res?.success) {
      error.value = res.error
    }

    return res
  }

  const getToken = (): string | null => {
    return localStorage.getItem('jwt')
  }

  const refreshSession = async (): Promise<boolean> => {
    const res = await authApi.refresh({
      refresh: localStorage.getItem('refresh') ?? 'unexist',
    })

    if (res.success) {
      localStorage.setItem('jwt', res.token as string)
      return true
    }

    logout()
    return false
  }

  const isAuthenticated = async (): Promise<boolean> => {
    const token = getToken()

    if (!token) return false

    try {
      const decoded = JSON.parse(atob(token.split('.')[1])) as JwtPayload
      const exp = decoded.exp
      const hasExp = exp < Date.now() / 1000

      if (!hasExp) return true

      const refreshed = await refreshSession()

      if (!refreshed) return false

      return true
    } catch {
      logout()
      return false
    }
  }

  const logout = (): void => {
    localStorage.removeItem('jwt')
    localStorage.removeItem('refresh')

    const event = new Event('logout')

    window.dispatchEvent(event)

    useAuthStore().clearSession()

    getSocket().close()
  }

  const disconnect = (): void => {
    logout()
    router.push({ name: 'home' }).then(r => {})
  }

  const forgotPassword = async (email: string): Promise<boolean> => {
    const res = await authApi.forgot(email)

    return res.success
  }

  return {
    isLoading,
    error,
    login,
    register,
    getToken,
    refreshSession,
    isAuthenticated,
    logout,
    disconnect,
    forgotPassword,
  }
}

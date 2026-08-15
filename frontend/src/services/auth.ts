import { Api } from '@/utils/api'
import router from '@/router'
import { getSocket } from '@/plugins/socket'
import type { JwtPayload, LoginResponse } from '@/types'

export const login = async (
  username: string,
  password: string,
): Promise<LoginResponse | Response | null> => {
  try {
    const response = await Api.post('/auth/login').send({
      username: username,
      password: password,
    })

    if (response.ok) {
      const data = (await response.json()) as LoginResponse

      localStorage.setItem('jwt', data.token)
      localStorage.setItem('refresh', data.refresh)

      const event = new Event('login')

      window.dispatchEvent(event)

      return data
    }

    return response
  } catch (error) {
    console.error('Erreur de connexion:', error)
    return null
  }
}

export const getToken = (): string | null => {
  return localStorage.getItem('jwt')
}

export const refreshSession = async (): Promise<boolean> => {
  const res = await Api.post('/auth/refresh').send({
    refresh: localStorage.getItem('refresh'),
  })

  if (res.status === 200) {
    const data = (await res.json()) as { token: string }

    localStorage.setItem('jwt', data.token)
    return true
  }

  logout()
  return false
}

export const isAuthenticated = async (): Promise<boolean> => {
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

export const logout = (): void => {
  localStorage.removeItem('jwt')
  localStorage.removeItem('refresh')

  const event = new Event('logout')

  window.dispatchEvent(event)

  getSocket().close()
}

export const disconnect = (): void => {
  logout()
  router.push({ name: 'home' })
}

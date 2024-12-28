import { Api } from '@/utils/api'

export const login = async (username, password) => {
  try {
    const response = await Api.post('/auth/login').send({
      username: username,
      password: password,
    })

    if (response.ok) {
      const data = await response.json()

      localStorage.setItem('jwt', data.token)
      localStorage.setItem('refresh', data.refresh)

      return data
    }

    return null
  } catch (error) {
    console.error('Erreur de connexion:', error)
    return null
  }
}

export const getToken = () => {
  return localStorage.getItem('jwt')
}

export const refreshSession = async () => {
  const res = await Api.post('/auth/refresh').send({
    refresh: localStorage.getItem('refresh'),
  })

  if (res.status === 200) {
    const data = await res.json()

    localStorage.setItem('jwt', data.token)
    return
  }

  logout()
}

export const isAuthenticated = () => {
  const token = getToken()

  if (!token) return false

  try {
    const decoded = JSON.parse(atob(token.split('.')[1]))
    const exp = decoded.exp
    const hasExp = exp < Date.now() / 1000

    if (!hasExp) return true

    refreshSession().then(r => { console.log("New session") })

    return true // TODO: isAuthenticated ?

    /* eslint-disable */
  } catch (error) {
    logout()
    return false
  }
}

export const logout = () => {
  localStorage.removeItem('jwt')
  localStorage.removeItem('refresh')
}

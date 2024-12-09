import { Api } from "@/utils/api";

export const login = async (username, password) => {
  try {
    const response = await Api.post('/auth/login').send({
      username: username,
      password: password,
    })
    const data = await response.json()
    const token = data.token

    localStorage.setItem('jwt', token)

    return data
  } catch (error) {
    console.error('Erreur de connexion:', error)
    throw error
  }
}

export const getToken = () => {
  return localStorage.getItem('jwt')
}

export const isAuthenticated = () => {
  const token = getToken()

  if (!token) return false

  try {
    const decoded = JSON.parse(atob(token.split('.')[1]))
    const exp = decoded.exp

    return exp > Date.now() / 1000

    /* eslint-disable */
  } catch (error) {
    return false
  }
}

export const logout = () => {
  localStorage.removeItem('jwt')
}
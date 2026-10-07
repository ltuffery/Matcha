import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { Api } from '@/utils/api'

interface LoginCredentials {
  email: string
  password: string
}

interface RegisterCredentials {
  name: string
  email: string
  password: string
}

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()

  // State
  const user = ref<object | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const loading = ref<boolean>(false)
  const error = ref<string | null>(null)

  // Getters
  const isAuthenticated = computed(
    () => token.value !== null && user.value !== null,
  )
  const fullName = computed(() => user.value?.name ?? null)
  const userRole = computed(() => user.value?.role ?? null)

  // Private helper
  function persistToken(value: string | null): void {
    token.value = value

    if (value) {
      localStorage.setItem('token', value)
    } else {
      localStorage.removeItem('token')
    }
  }

  // Actions

  async function fetchMe(): Promise<void> {
    loading.value = true
    error.value = null

    try {
      user.value = await (await Api.get('/users/me').send()).json()
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ??
        'Impossible de recuperer le profil.'
      clearSession()
    } finally {
      loading.value = false
    }
  }

  async function login(credentials: LoginCredentials): Promise<void> {
    loading.value = true
    error.value = null

    try {
      const data = await (
        await Api.post('/auth/login').send(credentials)
      ).json()
      persistToken(data.token)
      user.value = data.user
      await router.push('/')
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ?? 'Identifiants incorrects.'
    } finally {
      loading.value = false
    }
  }

  async function register(credentials: RegisterCredentials): Promise<void> {
    loading.value = true
    error.value = null

    try {
      const data = await (
        await Api.post('/auth/register').send(credentials)
      ).json()
      persistToken(data.token)
      user.value = data.user
      await router.push('/')
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ?? "Erreur lors de l'inscription."
    } finally {
      loading.value = false
    }
  }

  async function logout(): Promise<void> {
    loading.value = true

    try {
      await Api.post('/auth/logout').send()
    } finally {
      clearSession()
      loading.value = false
      await router.push('/login')
    }
  }

  function clearSession(): void {
    user.value = null
    persistToken(null)
    error.value = null
  }

  return {
    // State
    user,
    token,
    loading,
    error,
    // Getters
    isAuthenticated,
    fullName,
    userRole,
    // Actions
    fetchMe,
    login,
    register,
    logout,
    clearSession,
  }
})

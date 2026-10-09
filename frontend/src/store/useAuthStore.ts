import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { apiProfile } from '@/api/profile'
import type { User } from '@/api/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const loading = ref<boolean>(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => user.value !== null)
  const fullName = computed(() => {
    if (!user.value) return ''
    return `${user.value.first_name} ${user.value.last_name}`
  })

  function clearSession(): void {
    user.value = null
    error.value = null
  }

  // GET /users/me
  async function fetchMe(): Promise<User | null> {
    loading.value = true
    error.value = null
    try {
      const data = await apiProfile.getProfile()
      user.value = data
      return data
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ??
        'Unable to retrieve the profile.'
      clearSession()
      return null
    } finally {
      loading.value = false
    }
  }

  // PUT|PATCH /users/me
  async function updateProfile(data: Partial<User>): Promise<User | null> {
    loading.value = true
    error.value = null
    try {
      const updatedUser = await apiProfile.updateProfile(data)
      user.value = updatedUser
      return updatedUser
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ?? 'Unable to update the profile.'
      return null
    } finally {
      loading.value = false
    }
  }

  // DELETE /users/me
  async function deleteAccount(): Promise<void> {
    loading.value = true
    error.value = null
    try {
      await apiProfile.deleteAccount()
      clearSession()
    } catch (err: unknown) {
      error.value =
        (err as { message?: string }).message ?? 'Unable to delete the account.'
    } finally {
      loading.value = false
    }
  }

  // PUT|PATCH /users/me/localisation
  async function updateLocalisation(lon: number, lat: number): Promise<void> {
    try {
      await apiProfile.updateLocalisation(lat, lon)
    } catch (err: unknown) {
      console.error('Geolocation error:', err)
    }
  }

  return {
    user,
    loading,
    error,
    isAuthenticated,
    fullName,

    clearSession,
    fetchMe,
    updateProfile,
    deleteAccount,
    updateLocalisation,
  }
})

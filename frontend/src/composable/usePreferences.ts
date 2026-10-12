import { apiPreferences, type UserPreferences } from '@/api/preferences'
import { ref } from 'vue'
import { apiProfile } from '@/api/profile'

export function usePreferences() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const fetchPreference = () => {
    return apiPreferences.getPreferences()
  }

  const updatePreferences = (preferences: Partial<UserPreferences>) => {
    return apiPreferences.updatePreferences(preferences)
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
    isLoading,
    error,
    fetchPreference,
    updatePreferences,
  }
}

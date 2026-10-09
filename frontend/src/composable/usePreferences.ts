import { apiPreferences, type UserPreferences } from '@/api/preferences'
import { ref } from 'vue'

export function usePreferences() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const fetchPreference = () => {
    return apiPreferences.getPreferences()
  }

  const updatePreferences = (preferences: Partial<UserPreferences>) => {
    return apiPreferences.updatePreferences(preferences)
  }

  return {
    isLoading,
    error,
    fetchPreference,
    updatePreferences,
  }
}

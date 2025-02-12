import { defineStore } from 'pinia'
import { ref } from 'vue'

export const usePreferencesStore = defineStore('preferences', () => {
  const preferences = ref({
    age_minimum: 0,
    age_maximum: 0,
    distance_maximum: 0,
    sexual_preferences: '',
    by_tags: false,
    lat: 0,
    lon: 0,
    is_custom_loc: 0,
  })

  function setPreferences(newPrefs) {
    preferences.value = { ...newPrefs }
  }

  function isChanged(newPrefs) {
    if (preferences.value.age_minimum !== newPrefs.age_minimum) return true
    if (preferences.value.age_maximum !== newPrefs.age_maximum) return true
    if (preferences.value.distance_maximum !== newPrefs.distance_maximum)
      return true
    if (preferences.value.sexual_preferences !== newPrefs.sexual_preferences)
      return true
    if (preferences.value.by_tags !== newPrefs.by_tags) return true
    if (preferences.value.lat !== newPrefs.lat) return true
    if (preferences.value.lon !== newPrefs.lon) return true
    if (preferences.value.is_custom_loc !== newPrefs.is_custom_loc) return true
    return false
  }

  return { preferences, setPreferences, isChanged }
})

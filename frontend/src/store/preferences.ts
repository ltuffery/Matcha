import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Preferences } from '@/types'

export const usePreferencesStore = defineStore('preferences', () => {
  const preferences = ref<Preferences>({
    age_minimum: 0,
    age_maximum: 0,
    distance_maximum: 0,
    sexual_preferences: '',
    by_tags: false,
    lat: 0,
    lon: 0,
    is_custom_loc: 0,
  })

  function setPreferences(newPrefs: Preferences) {
    preferences.value = { ...newPrefs }
  }

  function isChanged(newPrefs: Preferences): boolean {
    let p: keyof Preferences; // no compilo error
    for (p in newPrefs) {
      if (preferences.value[p] !== newPrefs[p]) {
        return true
      }
    }

    return false
  }

  return { preferences, setPreferences, isChanged }
})

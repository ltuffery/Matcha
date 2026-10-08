import { ApiClient } from '@/api/client'

export interface UserPreferences {
  age_minimum: number
  age_maximum: number
  distance_maximum: number
  by_tags: string
  sexual_preferences: string
  lat: number
  lon: number
  is_custom_loc: number
}

export const apiPreferences = {
  async getPreferences() {
    return ApiClient.get<UserPreferences>('/users/me/preferences').then(res =>
      res.json(),
    )
  },

  async updatePreferences(data: Partial<UserPreferences>) {
    return ApiClient.put<UserPreferences>('/users/me/preferences', data).then(
      res => res.json(),
    )
  },
}

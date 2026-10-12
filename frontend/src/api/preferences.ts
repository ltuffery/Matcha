import { ApiClient } from '@/api/client'

export type SexualPreference = 'M' | 'W' | 'O' | 'A'

export interface UserPreferences {
  age_minimum: number
  age_maximum: number
  distance_maximum: number
  by_tags: boolean
  sexual_preferences: SexualPreference
  lat: number
  lon: number
  is_custom_loc: boolean
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

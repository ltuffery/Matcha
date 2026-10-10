import type { User } from '@/api/auth'
import { ApiClient } from '@/api/client'

export const apiProfile = {
  async getProfile() {
    return ApiClient.get<User>('/users/me').then(res => res.json())
  },

  async updateProfile(data: Partial<User>) {
    return ApiClient.put<User>('/users/me', data).then(res => res.json())
  },

  async deleteAccount() {
    return ApiClient.delete('/users/me').then(res => res.json())
  },

  async setOffline() {
    return ApiClient.post('/users/me/offline').then(res => res.json())
  },

  async updateLocalisation(latitude: number, longitude: number) {
    return ApiClient.put('/users/me/localisation', {
      lat: latitude,
      lon: longitude,
    }).then(res => res.json())
  },
}

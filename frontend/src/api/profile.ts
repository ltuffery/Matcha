import type { User } from '@/api/auth'
import { ApiClient } from '@/api/client'

export interface SmallProfile {
  avatar: string
  username: string
  first_name: string
  last_name: string
  created_at: string
}

export interface HistoryResponse {
  likes: SmallProfile[]
  views: SmallProfile[]
}

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
      latitude,
      longitude,
    }).then(res => res.json())
  },

  async getHistory() {
    return ApiClient.get<HistoryResponse>(`/users/me/history`).then(res =>
      res.json(),
    )
  },
}

import { ApiClient } from '@/api/client'
import type { Preferences } from '@/types'
import type { User } from '@/api/auth'

export type Genre = 'M' | 'F' | 'O'

export interface Profile {
  username: string
  avatar: string
  photos: string[]
  first_name: string
  last_name: string
  age: number
  biography: string
  fame_rating: number
  distance: number
  me: boolean
  common_tags: string[]
  gender: Genre
  tags: string[]
  preferences?: Preferences
  email?: string
}

export const usersApi = {
  // --- View ---
  async getProfile(username: string) {
    return ApiClient.get<Profile>(`/users/${username}`).then(res => res.json())
  },

  async trackView(username: string) {
    return ApiClient.post(`/users/${username}/view`).then(res => res.json())
  },

  // --- Likes ---
  async like(username: string) {
    return ApiClient.post(`/users/${username}/like`).then(res => res.json())
  },

  async unlike(username: string) {
    return ApiClient.delete(`/users/${username}/unlike`).then(res => res.json())
  },

  // --- Block / Flags ---
  async getBlockedList() {
    return ApiClient.get<User[]>('/users/me/blocks').then(res => res.json())
  },

  async block(username: string) {
    return ApiClient.post(`/users/${username}/block`).then(res => res.json())
  },

  async unblock(username: string) {
    return ApiClient.delete(`/users/${username}/unblock`).then(res =>
      res.json(),
    )
  },

  async report(username: string, reason: string) {
    return ApiClient.post(`/users/${username}/report`, { reason }).then(res =>
      res.json(),
    )
  },
}

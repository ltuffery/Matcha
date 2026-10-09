import { ApiClient } from '@/api/client'
import type { Profile } from '@/api/users'

export interface SearchParams {
  distance: number
  years: number[]
  fame_rating: number[]
  tags: string[]
  sorts?: string[]
}

export const searchApi = {
  async find(params: SearchParams) {
    return ApiClient.get<Profile[]>('/search/users', params).then(r => r.json())
  }
}

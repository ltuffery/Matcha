import { ApiClient } from '@/api/client'

export interface Message {
  id: number
  sender: string
  avatar: string
  content: string
  view: boolean
  created_at: string
}

export interface MatchUser {
  avatar: string
  username: string
  first_name: string
  last_message: string
  unread: number
}

export const chatApi = {
  async getMatches() {
    return ApiClient.get<MatchUser[]>('/users/me/matches').then(res => res.json())
  },

  async getMessages(username: string) {
    return ApiClient.get<Message[]>(`/users/me/matches/${username}`).then(res =>
      res.json(),
    )
  },

  async sendMessage(username: string, text: string) {
    return ApiClient.post<Message>(`/users/me/matches/${username}`, {
      text,
    }).then(res => res.json())
  },

  async deleteMessage(username: string, messageId: number) {
    return ApiClient.delete(`/users/me/matches/${username}/${messageId}`).then(
      res => res.json(),
    )
  },
}

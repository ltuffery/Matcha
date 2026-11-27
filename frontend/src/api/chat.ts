import { ApiClient } from '@/api/client'
import type { Reaction } from '@/api/reactions'

export interface Message {
  id: number
  sender: string
  avatar: string
  content: string
  view: boolean
  reactions: Reaction[]
  created_at: string
}

export interface Conversation {
  avatar: string
  username: string
  first_name: string
  last_name: string
  messages: Message[]
}

export interface MatchUser {
  avatar: string
  username: string
  first_name: string
  last_name: string
  last_message: Message | null
  unread: number
}

export const chatApi = {
  async getMatches() {
    return ApiClient.get<MatchUser[]>('/users/me/matches').then(res => res.json())
  },

  async getMessages(username: string) {
    return ApiClient.get<Conversation>(`/users/me/matches/${username}`).then(res =>
      res.json(),
    )
  },

  async sendMessage(username: string, text: string) {
    return ApiClient.post<Message>(`/users/me/matches/${username}`, {
      content: text,
    }).then(res => res.json())
  },

  async deleteMessage(username: string, messageId: number) {
    return ApiClient.delete(`/users/me/matches/${username}/${messageId}`).then(
      res => res.json(),
    )
  },
}

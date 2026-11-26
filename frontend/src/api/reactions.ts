import {ApiClient} from "@/api/client";

export interface Reaction {
  username: string
  message_id: number
  emoji: string
  is_me: boolean
}

export const apiReaction = {
  async addReaction(username: string, messageId: number, emoji: string) {
    return ApiClient.post<Reaction>(
      `/users/me/matches/${username}/${messageId}/reactions`,
      {
        emoji: emoji
      }
    ).then(res => res.json())
  },
  async removeReaction(username: string, messageId: number) {
    return ApiClient.delete<Reaction>(
      `/users/me/matches/${username}/${messageId}/reactions`,
    ).then(res => res.json())
  }
}

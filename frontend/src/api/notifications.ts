import { ApiClient } from '@/api/client'

export interface Notifications {
  id: number
  type: string
  data: {
    username: string
    first_name: string
    last_name: string
    avatar: string
    content: string
    view: boolean
    created_at: string
  }
}

export const notificationsApi = {
  async getMyNotifications() {
    return ApiClient.get<Notifications[]>('/users/me/notifications').then(res =>
      res.json(),
    )
  },

  async markAsViewed(notificationId: number) {
    return ApiClient.post(
      `/users/me/notifications/${notificationId}/view`,
    ).then(res => res.json())
  },
}

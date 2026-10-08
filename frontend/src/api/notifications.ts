import { ApiClient } from '@/api/client'

export const notificationsApi = {
  async getMyNotifications() {
    return ApiClient.get('/users/me/notifications').then(res => res.json())
  },

  async markAsViewed(notificationId: number) {
    return ApiClient.post(
      `/users/me/notifications/${notificationId}/view`,
    ).then(res => res.json())
  },

  // Route POST /users/@username/notifications
  /**
   * @deprecated frontend cannot send notification a another user
   */
  async sendToUser(username: string, data: { type: string; content: string }) {
    return ApiClient.post(`/users/${username}/notifications`, data).then(res =>
      res.json(),
    )
  },
}

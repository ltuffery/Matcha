import { defineStore } from 'pinia'
import type { NotificationData } from '@/types'

export const notificationsStore = defineStore('notifications', {
  state: () => ({
    notifications: [] as NotificationData[],
  }),
  actions: {
    set(notifications: NotificationData[]) {
      this.notifications = notifications
    },
    add(notification: NotificationData) {
      this.notifications.push(notification)
    },
    hasNotificationNotView(): boolean {
      return this.notifications.some(notification => !notification.data.view)
    },
    getNotificationNotView(): NotificationData[] {
      return this.notifications.filter(notification => !notification.data.view)
    },
    updateView(notification: NotificationData) {
      for (const i in this.notifications) {
        if (this.notifications[i].id === notification.id) {
          this.notifications[i].data.view = true
        }
      }
    },
  },
})

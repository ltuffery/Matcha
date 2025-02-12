import {defineStore} from "pinia";

export const notificationsStore = defineStore('notifications', {
  state: () => ({
    notifications: [],
  }),
  actions: {
    set(notifications) {
      this.notifications = notifications
    },
    add(notification) {
      this.notifications.push(notification)
    },
    hasNotificationNotView() {
      return this.notifications.some(notification => !notification.data.view)
    },
    getNotificationNotView() {
      return this.notifications.filter(notification => !notification.data.view)
    },
    updateView(notification) {
      for (let i in this.notifications) {
        if (this.notifications[i].id === notification.id) {
          this.notifications[i].data.view = true
        }
      }
    },
  }
})

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
    setAllView() {
      this.notifications = this.notifications.map(notification => {
        notification.view = true
        return notification
      })
    },
    getNotificationNotView() {
      return this.notifications.filter(notification => !notification.data.view)
    }
  }
})

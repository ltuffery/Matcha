import { defineStore } from 'pinia'

export const useOnlineUsersStore = defineStore('onlineUsers', {
  state: () => ({
    onlineUsers: [] as string[],
  }),
  actions: {
    setOnlineUsers(users: string[]) {
      this.onlineUsers = users
    },
    addOnlineUser(username: string) {
      if (!this.onlineUsers.includes(username)) {
        this.onlineUsers.push(username)
      }
    },
    removeOnlineUser(username: string) {
      this.onlineUsers = this.onlineUsers.filter(id => id !== username)
    },
    isOnlineUser(username: string): boolean {
      return this.onlineUsers.includes(username)
    },
  },
})

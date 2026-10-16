import { defineStore } from 'pinia'

type PresenceStatus = 'online' | 'offline'

export const usePresenceStore = defineStore('presenceStore', {
  state: () => ({
    onlineUsers: [] as string[],
  }),
  actions: {
    setUsers(users: string[]) {
      this.onlineUsers = users
    },
    addUser(username: string) {
      if (!this.onlineUsers.includes(username)) {
        this.onlineUsers.push(username)
      }
    },
    setUser(username: string, status: PresenceStatus) {
      if (status == 'online') {
        this.addUser(username)
      } else {
        this.removeUser(username)
      }
    },
    removeUser(username: string) {
      this.onlineUsers = this.onlineUsers.filter(id => id !== username)
    },
    isOnline(username: string): boolean {
      return this.onlineUsers.includes(username)
    },
  },
})

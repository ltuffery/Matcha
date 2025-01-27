import { defineStore } from 'pinia'

export const likesStore = defineStore('likes', {
  state: () => ({
    users: [],
  }),
  actions: {
    set(users) {
      this.users = users
    },
    add(user) {
      this.users.push(user)
    },
    remove(username) {
      this.users = this.users.filter(u => u.username !== username)
    },
  },
})

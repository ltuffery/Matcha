import { defineStore } from 'pinia'
import type { User } from '@/types'

export const likesStore = defineStore('likes', {
  state: () => ({
    users: [] as User[],
  }),
  actions: {
    set(users: User[]) {
      this.users = users
    },
    add(user: User) {
      this.users.push(user)
    },
    remove(username: string) {
      this.users = this.users.filter(u => u.username !== username)
    },
  },
})

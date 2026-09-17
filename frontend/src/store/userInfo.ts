import { defineStore } from 'pinia'
import type { User } from '@/types'

export const useUserInfoStore = defineStore('userInfos', {
  state: () => ({
    user: {} as User,
  }),
  actions: {
    set(user: User) {
      this.user = user
    },
    add(info: Partial<User>) {
      Object.assign(this.user, info)
    },
    remove(key: keyof User) {
      delete this.user[key]
    },
  },
})

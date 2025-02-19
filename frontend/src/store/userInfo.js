import { defineStore } from 'pinia'

export const useUserInfoStore = defineStore('userInfos', {
  state: () => ({
    user: {},
  }),
  actions: {
    set(user) {
      this.user = user
    },
    add(info) {
      Object.assign(this.user, info)
    },
    remove(key) {
      delete this.user[key]
    },
  },
})

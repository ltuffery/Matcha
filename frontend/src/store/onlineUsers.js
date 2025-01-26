import { defineStore } from "pinia";

export const useOnlineUsersStore = defineStore("onlineUsers", {
  state: () => ({
    onlineUsers: [],
  }),
  actions: {
    setOnlineUsers(users) {
      this.onlineUsers = users;
    },
    addOnlineUser(username) {
      if (!this.onlineUsers.includes(username)) {
        this.onlineUsers.push(username);
      }
    },
    removeOnlineUser(username) {
      this.onlineUsers = this.onlineUsers.filter((id) => id !== username);
    },
    isOnlineUser(username) {
      return this.onlineUsers.includes(username)
    }
  },
})

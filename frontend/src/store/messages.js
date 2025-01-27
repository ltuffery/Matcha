import { defineStore } from 'pinia'

export const useMessagesStore = defineStore('messagesStore', {
  state: () => ({
    messages: {messages: []}
  }),
  actions: {
    set(messages) {
      this.messages = messages

      this.messages.messages = this.messages.messages.reverse()
    },
    add(message) {
      this.messages.messages.push(message)
    }
  },
})

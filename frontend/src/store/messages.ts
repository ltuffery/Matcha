import { defineStore } from 'pinia'
import type { MessagesResponse, MessageData } from '@/types'

export const useMessagesStore = defineStore('messagesStore', {
  state: () => ({
    messages: { messages: [] } as MessagesResponse,
  }),
  actions: {
    set(messages: MessagesResponse) {
      this.messages = messages
      this.messages.messages = this.messages.messages.reverse()
    },
    add(message: MessageData) {
      this.messages.messages.push(message)
    },
  },
})

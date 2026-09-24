import { defineStore } from 'pinia'
import type { MessagesResponse, MessageData } from '@/types'

export const useMessagesStore = defineStore('messagesStore', {
  state: () => ({
    conversations: new Map<string, MessageData[]>(),
  }),
  actions: {
    async sendMessage(toUsername: string, content: string) {
      const res = await Api.post(`/users/me/matches/${toUsername}`).send({ content });
      if (res.ok) {
        const saved: MessageData = await res.json();
        this.addMessage(toUsername, saved);
      }
      return res;
    },
    addMessage(otherUsername: string, message: MessageData) {
      if (!this.conversations.has(otherUsername)) {
        this.conversations.set(otherUsername, []);
      }
      this.conversations.get(otherUsername)!.push(message);
    },
    getMessages(otherUsername: string): MessageData[] {
      return this.conversations.get(otherUsername) ?? [];
    },

    setMessages(otherUsername: string, messages: MessageData[]) {
      this.conversations.set(otherUsername, messages);
    },
  },
})

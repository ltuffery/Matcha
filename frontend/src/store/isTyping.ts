import { defineStore } from "pinia";

export const useTypingStore = defineStore("typing", {
  state: () => ({
    typingUsers: new Set<string>(),
    timeouts: new Map<string, ReturnType<typeof setTimeout>>(),
  }),
  actions: {
    addTypingUser(from_username: string) {
      this.typingUsers.add(from_username);
      clearTimeout(this.timeouts.get(from_username));
      this.timeouts.set(
        from_username,
        setTimeout(() => this.typingUsers.delete(from_username), 4000)
      );
    },
    stopTyping(from_username: string) {
      clearTimeout(this.timeouts.get(from_username));
      this.typingUsers.delete(from_username);
    },
    isTyping(username: string) {
      return this.typingUsers.has(username);
    },
  },
});

import { defineStore, acceptHMRUpdate } from 'pinia'
import { ref } from 'vue'
import {
  chatApi,
  type Conversation,
  type MatchUser,
  type Message,
} from '@/api/chat'

export const useChatStore = defineStore('chat', () => {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const matches = ref<MatchUser[]>([])
  const activeConversation = ref<Conversation | null>(null)

  const getErrorMessage = (err: any, fallback: string) =>
    err?.response?.data?.message || fallback

  const bumpMatch = (
    username: string,
    message: Message,
    incrementUnread = false,
  ) => {
    const match = matches.value.find(u => u.username === username)
    if (!match) return

    match.last_message = message
    if (incrementUnread) match.unread++

    matches.value = [match, ...matches.value.filter(u => u !== match)]
  }

  const fetchMatches = async () => {
    isLoading.value = true
    error.value = null
    try {
      matches.value = await chatApi.getMatches()
      return matches.value
    } catch (err: any) {
      error.value = getErrorMessage(
        err,
        'Impossible de charger vos conversations.',
      )
      return []
    } finally {
      isLoading.value = false
    }
  }

  const fetchConversation = async (username: string) => {
    isLoading.value = true
    error.value = null
    try {
      activeConversation.value = await chatApi.getMessages(username)
      return activeConversation.value
    } catch (err: any) {
      error.value = getErrorMessage(err, 'Impossible de charger les messages.')
      return null
    } finally {
      isLoading.value = false
    }
  }

  const sendMessage = async (username: string, text: string) => {
    error.value = null
    try {
      const newMessage = await chatApi.sendMessage(username, text)
      receiveOwnMessage(username, newMessage)
      return newMessage
    } catch (err: any) {
      error.value = getErrorMessage(err, "Impossible d'envoyer le message.")
      return null
    }
  }

  const deleteMessage = async (username: string, messageId: number) => {
    error.value = null
    try {
      await chatApi.deleteMessage(username, messageId)
      removeMessage(messageId)
      return true
    } catch (err: any) {
      error.value = getErrorMessage(err, 'Impossible de supprimer le message.')
      return false
    }
  }

  const receiveOwnMessage = (username: string, message: Message) => {
    const conv = activeConversation.value
    if (
      conv?.username === username &&
      !conv.messages.some(m => m.id === message.id)
    ) {
      conv.messages.push(message)
    }
    bumpMatch(username, message)
  }

  const receiveMessage = (message: Message) => {
    const conv = activeConversation.value
    const isConvOpen = conv?.username === message.sender

    if (isConvOpen && !conv!.messages.some(m => m.id === message.id)) {
      conv!.messages.push(message)
    }
    bumpMatch(message.sender, message, !isConvOpen)
  }

  const removeMessage = (messageId: number) => {
    if (!activeConversation.value) return
    activeConversation.value.messages =
      activeConversation.value.messages.filter(m => m.id !== messageId)
  }

  const clearActiveConversation = () => {
    activeConversation.value = null
  }

  const $reset = () => {
    isLoading.value = false
    error.value = null
    matches.value = []
    activeConversation.value = null
  }

  return {
    isLoading,
    error,
    matches,
    activeConversation,
    fetchMatches,
    fetchConversation,
    sendMessage,
    deleteMessage,
    receiveMessage,
    removeMessage,
    clearActiveConversation,
    $reset,
  }
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useChatStore, import.meta.hot))
}

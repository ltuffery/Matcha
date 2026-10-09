import { ref } from 'vue'
import { chatApi, type MatchUser, type Message } from '@/api/chat'

export function useChat() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const matches = ref<MatchUser[]>([])
  const activeMessages = ref<Message[]>([])

  const fetchMatches = async () => {
    isLoading.value = true
    error.value = null
    try {
      const data = await chatApi.getMatches()
      matches.value = data
      return data
    } catch (err: any) {
      error.value =
        err.response?.data?.message ||
        'Impossible de charger vos conversations.'
      return []
    } finally {
      isLoading.value = false
    }
  }

  const fetchMessages = async (username: string) => {
    isLoading.value = true
    error.value = null
    try {
      const data = await chatApi.getMessages(username)
      activeMessages.value = data
      return data
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Impossible de charger les messages.'
      return []
    } finally {
      isLoading.value = false
    }
  }

  const sendMessage = async (username: string, text: string) => {
    error.value = null
    try {
      const newMessage = await chatApi.sendMessage(username, text)
      activeMessages.value.push(newMessage)
      return newMessage
    } catch (err: any) {
      error.value =
        err.response?.data?.message || "Impossible d'envoyer le message."
      return null
    }
  }

  const deleteMessage = async (username: string, messageId: number) => {
    error.value = null
    try {
      await chatApi.deleteMessage(username, messageId)
      activeMessages.value = activeMessages.value.filter(
        msg => msg.id !== messageId,
      )
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Impossible de supprimer le message.'
    }
  }

  return {
    isLoading,
    error,
    matches,
    activeMessages,

    fetchMatches,
    fetchMessages,
    sendMessage,
    deleteMessage,
  }
}

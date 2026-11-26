import { ref } from 'vue'
import {
  chatApi,
  type Conversation,
  type MatchUser,
} from '@/api/chat'

export function useChat() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const matches = ref<MatchUser[]>([])
  const activeConversation = ref<Conversation>()

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

  const fetchConversation = async (username: string) => {
    isLoading.value = true
    error.value = null
    try {
      const data = await chatApi.getMessages(username)
      activeConversation.value = data
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
      activeConversation.value?.messages.push(newMessage)
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
      activeConversation.value!.messages =
        activeConversation.value?.messages.filter(
          msg => msg.id !== messageId,
        ) ?? []
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Impossible de supprimer le message.'
    }
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
  }
}

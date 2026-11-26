import { ref } from 'vue'
import { apiReaction } from '@/api/reactions'

export function useReaction() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const addReaction = async (
    username: string,
    messageId: number,
    emoji: string,
  ) => {
    error.value = null
    try {
      return apiReaction.addReaction(username, messageId, emoji)
    } catch (err: any) {
      error.value =
        err.response?.data?.message || "Impossible de reagir."
      return null
    }
  }

  const removeReaction = async (
    username: string,
    messageId: number,
  ) => {
    error.value = null
    try {
      return apiReaction.removeReaction(username, messageId)
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Impossible de reagir.'
      return null
    }
  }

  return {
    isLoading,
    error,

    addReaction,
    removeReaction,
  }
}

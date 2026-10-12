import { apiProfile } from '@/api/profile'
import { ref } from 'vue'

export function useMe() {
  const isLoading = ref(false)

  const fetchHistory = () => {
    return apiProfile.getHistory()
  }

  return {
    isLoading,

    fetchHistory,
  }
}

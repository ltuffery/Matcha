import { ref } from 'vue'
import { type Profile } from '@/api/users'
import { searchApi, type SearchParams } from '@/api/search'

export function useSearch() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const userProfile = ref<Profile[] | null>(null)

  const fetch = async (params: SearchParams) => {
    isLoading.value = true
    error.value = null
    try {
      const data = await searchApi.find(params)
      userProfile.value = data
      return data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Unable to load the profile.'
      return null
    } finally {
      isLoading.value = false
    }
  }

  return {
    isLoading,
    error,

    fetch,
  }
}

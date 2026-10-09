import { ref } from 'vue'
import { usersApi } from '@/api/users'
import type { Profile, SuggestionParams } from '@/api/users'

export function useUsers() {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const userProfile = ref<any>(null)
  const blockedUsers = ref<any[]>([])
  const suggestedProfiles = ref<Profile[]>([])

  // --- Consultation ---
  const fetchProfile = async (username: string) => {
    isLoading.value = true
    error.value = null
    try {
      const data = await usersApi.getProfile(username)
      userProfile.value = data
      return data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Unable to load the profile.'
      return null
    } finally {
      isLoading.value = false
    }
  }

  const trackView = async (username: string) => {
    try {
      await usersApi.trackView(username)
    } catch (err) {
      console.error('Erreur tracking view:', err)
    }
  }

  // --- Likes ---
  const like = async (who: string) => {
    isLoading.value = true
    error.value = null
    try {
      await usersApi.like(who)
    } catch (err: any) {
      error.value = err.response?.data?.message || "You can't like this user."
    } finally {
      isLoading.value = false
    }
  }

  const unlike = async (who: string) => {
    isLoading.value = true
    error.value = null
    try {
      await usersApi.unlike(who)
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Unable to “unlike” this user.'
    } finally {
      isLoading.value = false
    }
  }

  // --- Modération (Blocages et Signalement) ---
  const fetchBlockedList = async () => {
    isLoading.value = true
    error.value = null
    try {
      const data = await usersApi.getBlockedList()
      blockedUsers.value = data
      return data
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Unable to load the blacklist.'
      return []
    } finally {
      isLoading.value = false
    }
  }

  const block = async (who: string) => {
    isLoading.value = true
    error.value = null
    try {
      await usersApi.block(who)
      // Optionnel : Retirer de la liste locale si besoin
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Impossible de bloquer cet utilisateur.'
    } finally {
      isLoading.value = false
    }
  }

  const unblock = async (who: string) => {
    isLoading.value = true
    error.value = null
    try {
      await usersApi.unblock(who)
      // Optionnel : Mettre à jour la liste localement si fetchBlockedList a été appelé
      blockedUsers.value = blockedUsers.value.filter(
        user => user.username !== who,
      )
    } catch (err: any) {
      error.value =
        err.response?.data?.message ||
        'Impossible de débloquer cet utilisateur.'
    } finally {
      isLoading.value = false
    }
  }

  const report = async (who: string, reason: string) => {
    isLoading.value = true
    error.value = null
    try {
      await usersApi.report(who, reason)
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Impossible de signaler cet utilisateur.'
    } finally {
      isLoading.value = false
    }
  }

  const suggestion = async (params: SuggestionParams) => {
    isLoading.value = true
    error.value = null
    try {
      const data = await usersApi.suggestion(params);
      suggestedProfiles.value = data.profiles;
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'suggestion error'
    } finally {
      isLoading.value = false
    }
  }

  return {
    // États réactifs
    isLoading,
    error,
    userProfile,
    blockedUsers,
    suggestedProfiles,

    // Méthodes
    fetchProfile,
    trackView,
    like,
    unlike,
    fetchBlockedList,
    block,
    unblock,
    report,
    suggestion,
  }
}

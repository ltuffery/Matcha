import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { notificationsApi } from '@/api/notifications'

export const useNotificationStore = defineStore('notifications', () => {
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const notifications = ref<any[]>([])
  const unreadCount = computed(() => {
    return notifications.value.filter(notif => !notif.is_viewed).length
  })

  const fetchNotifications = async () => {
    isLoading.value = true
    error.value = null
    try {
      const data = await notificationsApi.getMyNotifications()
      notifications.value = data
      return data
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Unable to load your notifications.'
      return []
    } finally {
      isLoading.value = false
    }
  }

  const markAsViewed = async (notificationId: number) => {
    error.value = null
    try {
      await notificationsApi.markAsViewed(notificationId)

      const target = notifications.value.find(
        notif => notif.id === notificationId,
      )
      if (target) {
        target.is_viewed = true
      }
    } catch (err: any) {
      error.value =
        err.response?.data?.message || 'Unable to update the notification.'
    }
  }

  return {
    isLoading,
    error,
    notifications,
    unreadCount,

    fetchNotifications,
    markAsViewed,
  }
})

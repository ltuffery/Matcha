<script setup lang="ts">
import { Bell } from '@lucide/vue'
import { useNotificationStore } from '@/store/useNotificationStore'

const notificationStore = useNotificationStore()
</script>

<template>
  <div class="relative">
    <Bell />
    <span
      v-if="notificationStore.unreadCount"
      class="absolute -top-1 -right-1 h-4 min-w-4 px-1 rounded-full bg-primary text-primary-foreground text-[10px] flex items-center justify-center"
    >
      {{ notificationStore.unreadCount }}
    </span>
  </div>
</template>

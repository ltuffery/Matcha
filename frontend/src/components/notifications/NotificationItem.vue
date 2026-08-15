<script setup lang="ts">
import Avatar from '@/components/Avatar.vue'
import { Api } from '@/utils/api'
import { computed } from 'vue'
import { notificationsStore } from '@/store/notifications'
import type { NotificationData } from '@/types'

const props = defineProps<{
  notification: NotificationData
}>()

const isNew = computed(() => !props.notification.data.view)

const markAsRead = async (notification: NotificationData) => {
  const res = await Api.post(
    `/users/me/notifications/${notification.id}/view`,
  ).send()

  if (res.ok) {
    notificationsStore().updateView(notification)
  }
}
</script>

<template>
  <li>
    <a
      class="inline-flex items-center gap-4 w-full"
      @click="markAsRead(notification)"
    >
      <Avatar
        type="squircle"
        :username="notification.data.username"
        width="12"
        :src="notification.data.avatar"
      />

      <div class="flex flex-col w-full overflow-hidden">
        <p class="font-semibold truncate">{{ notification.data.content }}</p>
        <p>{{ notification.data.created_at }}</p>
      </div>

      <div class="badge badge-error badge-xs left-0" v-if="isNew"></div>
    </a>
  </li>
</template>

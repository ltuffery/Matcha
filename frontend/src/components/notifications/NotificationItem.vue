<script setup>
import Avatar from "@/components/Avatar.vue";
import {Api} from "@/utils/api.js";
import {computed} from "vue";
import {notificationsStore} from "@/store/notifications.js";

const props = defineProps({
  notification: {
    type: Object,
    required: true,
  }
})

const isNew = computed(() => !props.notification.data.view)

const markAsRead = async notification => {
  const res = await Api.post(`/users/me/notifications/${notification.id}/view`).send()

  if (res.ok) {
    notificationsStore().updateView(notification)
  }
}
</script>

<template>
  <li>
    <a class="inline-flex items-center gap-4 w-full" @click="markAsRead(notification)">
      <Avatar type="squircle" :username="notification.data.username" width="12" :src="notification.data.avatar"/>

      <div class="flex flex-col w-full overflow-hidden">
        <p class="font-semibold truncate">{{ notification.data.content }}</p>
        <p>{{ notification.data.created_at }}</p>
      </div>

      <div class="badge badge-error badge-xs left-0" v-if="isNew"></div>
    </a>
  </li>
</template>

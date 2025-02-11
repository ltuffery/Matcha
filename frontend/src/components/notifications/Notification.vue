<script setup>
import {computed, onMounted, ref} from "vue";
import {Api} from "@/utils/api.js";
import {notificationsStore} from "@/store/notifications.js";
import {getSocket} from "@/plugins/socket.js";
import Avatar from "@/components/Avatar.vue";

const notifications = computed(() => {
  return notificationsStore().notifications.slice(0, Math.min(notificationsStore().notifications.length, 5))
})

onMounted(async () => {
  const res = await Api.get('/users/me/notifications').send()
  const data = await res.json()

  notificationsStore().set(data.reverse())

  getSocket().on('notification', notification => {
    notificationsStore().add(notification)
  })
})
</script>

<template>
  <div class="dropdown">
    <button tabindex="0" class="btn btn-ghost z-20">
      <svg class="w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g stroke-width="0"></g>
        <g stroke-linecap="round" stroke-linejoin="round"></g>
        <g>
          <path
              d="M9.10745 2.67414C9.98414 2.24187 10.9649 2 12 2C15.7274 2 18.7491 5.13623 18.7491 9.00497V9.70957C18.7491 10.5552 18.9903 11.3818 19.4422 12.0854L20.5496 13.8095C21.5612 15.3843 20.789 17.5249 19.0296 18.0229C14.4273 19.3257 9.57274 19.3257 4.97036 18.0229C3.21105 17.5249 2.43882 15.3843 3.45036 13.8095L4.5578 12.0854C5.00972 11.3818 5.25087 10.5552 5.25087 9.70957V9.00497C5.25087 7.93058 5.48391 6.91269 5.90039 6.00277"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
          <path
              d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C12.2445 22 12.4847 21.9827 12.7192 21.9492M16.5 19C16.2329 19.7126 15.781 20.3428 15.1985 20.8393"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
        </g>
      </svg>
      <!--      <div class="badge badge-error badge-sm" v-if="notificationsStore().hasNotificationNotView()"></div>-->
    </button>
    <div tabindex="0" class="menu dropdown-content bg-base-100 rounded-box z-20 max-w-96 p-2 shadow">
      <h3 class="font-bold text-center py-2">Notification</h3>
      <div class="divider my-0"></div>
      <ul class="w-full overflow-y-auto">
        <li v-for="(notification, index) in notifications" :key="index">
          <a class="inline-flex gap-4 w-full">
            <Avatar type="squircle" :username="notification.data.username" width="12" :src="notification.data.avatar"/>

            <div class="flex flex-col w-full overflow-hidden">
              <p class="font-semibold truncate">{{ notification.data.content }}</p>
              <p>{{ notification.data.created_at }}</p>
            </div>
          </a>
        </li>
      </ul>
      <div class="divider my-0"></div>
      <a href="#" class="text-center py-2 underline">View all</a>
    </div>
  </div>
</template>

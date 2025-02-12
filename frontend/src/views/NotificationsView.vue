<script setup>
import {computed} from "vue";
import {notificationsStore} from "@/store/notifications.js";
import Avatar from "@/components/Avatar.vue";
import {Api} from "@/utils/api.js";

const notifications = computed(() => notificationsStore().notifications.sort((a, b) => {
  return new Date(b.data.created_at) - new Date(a.data.created_at)
}))

const markAsRead = async notification => {
  const res = await Api.post(`/users/me/notifications/${notification.id}/view`).send()

  if (res.ok) {
    notificationsStore().updateView(notification)
  }
}
</script>

<template>
  <div class="overflow-x-auto">
    <table class="table">
      <!-- head -->
      <thead>
        <tr>
          <th>Profile</th>
          <th>Content</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
      <!-- row 1 -->
        <tr v-for="(notification, index) in notifications" :key="index">
          <td>
            <div class="flex items-center gap-3">
              <Avatar type="squircle" :username="notification.data.username" :src="notification.data.avatar" width="12" />
              <div>
                <div class="font-bold">{{ notification.data.username }}</div>
                <div class="text-sm opacity-50">{{ notification.data.created_at }}</div>
              </div>
            </div>
          </td>
          <td>
            {{ notification.data.content }}
          </td>
          <th class="text-end" v-if="!notification.data.view">
            <button class="btn btn-ghost" @click="markAsRead(notification)">
              <svg class="w-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g stroke-width="0"></g>
                <g stroke-linecap="round" stroke-linejoin="round"></g>
                <g>
                  <path
                    d="M9 4.45962C9.91153 4.16968 10.9104 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C3.75612 8.07914 4.32973 7.43025 5 6.82137"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path
                    d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                    stroke="currentColor" stroke-width="1.5"></path>
                </g>
              </svg>
            </button>
          </th>
        </tr>
      </tbody>
    </table>
  </div>
</template>

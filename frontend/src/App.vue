<script setup lang="ts">
import { RouterView } from 'vue-router'
import { onMounted, onUnmounted, watch } from 'vue'
import { type BasicColorSchema, useColorMode } from '@vueuse/core'
import { toast, Toaster } from 'vue-sonner'
import 'vue-sonner/style.css'
import NavBar from '@/components/layout/NavBar.vue'
import { useAuthStore } from '@/store/useAuthStore'
import { storeToRefs } from 'pinia'

const mode = useColorMode()
const authStore = useAuthStore()
const { isAuthenticated, user } = storeToRefs(authStore)

let eventSource: EventSource | null = null

function openNotifications(userId: number | string) {
  closeNotifications()
  eventSource = new EventSource(
    `/api/.well-known/mercure?topic=${encodeURIComponent(`user/${userId}/notifications`)}`,
  )
  eventSource.onmessage = event => {
    const data = JSON.parse(event.data)
    toast.info(data.type, {
      description: data.message,
      closeButton: true,
    })
  }
}

function closeNotifications() {
  eventSource?.close()
  eventSource = null
}

watch(
  isAuthenticated,
  authenticated => {
    if (authenticated && user.value) openNotifications(user.value.id)
    else closeNotifications()
  },
  { immediate: true },
)

onMounted(async () => {
  const theme = localStorage.getItem('theme') as BasicColorSchema | null
  mode.value = theme ?? 'dark'

  if (!authStore.user) {
    try {
      await authStore.fetchMe()
    } catch {
      /* no connect */
    }
  }
})

onUnmounted(closeNotifications)
</script>

<template>
  <Toaster position="top-right" :theme="mode == 'auto' ? 'system' : mode" />

  <div :class="{ 'flex h-screen overflow-y-auto': isAuthenticated }">
    <NavBar v-if="isAuthenticated" />

    <main
      :class="{
        'flex w-full pb-20 md:pb-0 px-6 md:px-20 mx-auto max-w-6xl py-10':
          isAuthenticated,
      }"
    >
      <RouterView />
    </main>
  </div>
</template>

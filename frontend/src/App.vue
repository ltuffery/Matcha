<script setup lang="ts">
import { RouterView } from 'vue-router'
import { isAuthenticated } from '@/services/auth'
import { connectSocket } from '@/plugins/socket'
import NavBar from '@/components/layout/NavBar.vue'
import { onMounted, onUnmounted, ref } from 'vue'
import Footer from '@/components/layout/Footer.vue'
import { Tracking } from '@/services/tracking'
import { type BasicColorSchema, useColorMode } from '@vueuse/core'
import DateOfBirthPicker from '@/components/forms/DateOfBirthPicker.vue'
import { toast, Toaster } from 'vue-sonner'
import 'vue-sonner/style.css'
import { useUserInfoStore } from '@/store/userInfo'

const mode = useColorMode()
const breakPointScreen = '(min-width: 70em)'

const sizeScreen = ref<MediaQueryList>(window.matchMedia(breakPointScreen))

const isAuth = ref(false)

isAuthenticated().then(value => {
  isAuth.value = value
  if (value) {
    Tracking.setAtCurrentLocation()
    connectSocket()

    const eventSource = new EventSource(
      `/api/.well-known/mercure?topic=${encodeURIComponent("user/gcummerata/notifications")}`,
    )
    eventSource.onmessage = function (event) {
      const data = JSON.parse(event.data)

      toast(data.type, {
        description: data.message,
        action: {
          label: 'Undo',
          onClick: () => console.log('Undo'),
        },
      })

      console.log('New message:', event.data)
    }
  }
})

window.addEventListener('login', () => {
  isAuth.value = true
})

window.addEventListener('logout', () => {
  isAuth.value = false
})

onMounted(async () => {
  const mediaQuery = window.matchMedia(breakPointScreen)

  mediaQuery.addEventListener('change', () => {
    sizeScreen.value = mediaQuery
  })

  const theme = localStorage.getItem('theme') as BasicColorSchema | null
  mode.value = theme !== null ? theme : 'dark'
})

onUnmounted(() => {
  const mediaQuery = window.matchMedia(breakPointScreen)
  mediaQuery.removeEventListener('change', () => {
    sizeScreen.value = mediaQuery
  })
})
</script>

<template>
  <Toaster position="top-right" :theme="mode == 'auto' ? 'system' : mode" />

  <div :class="{ 'flex h-screen': isAuth }">
    <NavBar v-if="isAuth" />

    <main
      :class="{
        'flex w-full pb-20 md:pb-0 px-6 md:px-20 bg-muted/30': isAuth,
      }"
    >
      <RouterView />
    </main>

    <!--  <Footer class="z-0" v-if="sizeScreen.matches" />-->
  </div>
</template>

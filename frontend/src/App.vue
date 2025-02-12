<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { connectSocket } from '@/plugins/socket.js'
import NavBar from '@/components/NavBar.vue'
import { onMounted, onUnmounted, ref } from 'vue'
import FooterView from '@/components/FooterView.vue'
import {Tracking} from "@/services/tracking.js"
import Notification from '@/components/notifications/Notification.vue'

const breakPointScreen = '(min-width: 70em)'

const sizeScreen = ref(window.matchMedia(breakPointScreen))

const isAuth = ref(false)

isAuthenticated().then(value => {
  isAuth.value = value
  if (value) {
    Tracking.setAtCurrentLocation()
    connectSocket()
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
  mediaQuery.addEventListener('change', e => {
    sizeScreen.value = e
  })
})

onUnmounted(() => {
  const mediaQuery = window.matchMedia(breakPointScreen)
  mediaQuery.removeEventListener('change', e => {
    sizeScreen.value = e
  })
})
</script>

<template>
  <NavBar large-screen v-if="isAuth && sizeScreen.matches" />
  <div
    class="flex flex-col bg-base-300 h-dvh w-full justify-center items-center"
  >
    <div
      class="overflow-y-auto relative bg-base-200 h-full w-full max-w-3xl z-10"
    >
      <Notification v-if="isAuth" class="absolute" />
      <RouterView />
    </div>
    <NavBar v-if="isAuth && !sizeScreen.matches" />
  </div>
  <FooterView class="z-0" v-if="sizeScreen.matches" />
</template>

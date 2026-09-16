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

const mode = useColorMode()
const breakPointScreen = '(min-width: 70em)'

const sizeScreen = ref<MediaQueryList>(window.matchMedia(breakPointScreen))

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
  <div :class="{ 'flex min-h-screen': isAuth }">
    <NavBar v-if="isAuth" />

    <main :class="{ 'flex-1 pb-16 md:pb-0 px-6 md:px-20 bg-muted/30 h-dvh': isAuth }">
      <RouterView />
    </main>

    <!--  <Footer class="z-0" v-if="sizeScreen.matches" />-->
  </div>
</template>

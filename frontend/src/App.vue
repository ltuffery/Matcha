<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { Api } from './utils/api'
import { connectSocket, getSocket } from '@/plugins/socket.js'
import FeedbackToast from '@/components/FeedbackToast.vue'
import NavBar from '@/components/NavBar.vue'
import { onMounted, ref, useTemplateRef } from 'vue'

const breakPointScreen = '(min-width: 70em)'

const sizeScreen = ref(window.matchMedia(breakPointScreen))

const isAuth = ref(false)

isAuthenticated().then(value => {
  isAuth.value = value
  if (value) {
    navigator.geolocation.getCurrentPosition(
      loc => {
        Api.put('/users/me/localisation').send({
          lat: loc.coords.latitude,
          lon: loc.coords.longitude,
        })
      },
      err => {
        Api.put('/users/me/localisation').send()
      },
    )
    connectSocket()
  }
})

let toast = useTemplateRef('toast')

onMounted(() => {
  const isAuth = await isAuthenticated()

  if (isAuth) {
    getSocket().on('notification', notification => {
      console.log(toast.value)
      toast.value.addInfo('Hey !')
    })
  }

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
  <FeedbackToast ref="toast" class="w-full" />
  <div
    class="flex flex-col bg-base-300 h-dvh w-full justify-center items-center"
  >
    <div class="overflow-y-auto relative bg-base-200 h-full w-full max-w-3xl">
      <RouterView />
    </div>
    <NavBar v-if="isAuth && !sizeScreen.matches" />
  </div>
</template>

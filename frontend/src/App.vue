<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { Api } from './utils/api'
import { connectSocket, getSocket } from '@/plugins/socket.js'
import FeedbackToast from '@/components/FeedbackToast.vue'
import { onMounted, ref, useTemplateRef } from 'vue'

isAuthenticated().then(value => {
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

onMounted(async () => {
  const isAuth = await isAuthenticated()

  if (isAuth) {
    getSocket().on('notification', notification => {
      console.log(toast.value)
      toast.value.addInfo('Hey !')
    })
  }
})
</script>

<template>
  <FeedbackToast ref="toast" class="w-full" />
  <RouterView />
</template>

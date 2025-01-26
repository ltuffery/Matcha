<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { Api } from './utils/api'
import {connectSocket, getSocket} from "@/plugins/socket.js";

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
    getSocket().emit('online')
  }
})
</script>

<template>
  <RouterView />
</template>

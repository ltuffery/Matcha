<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { socket } from './services/socket'
import { Api } from './utils/api'

isAuthenticated().then(v => {
  if (v) {
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
    socket.emit('online')
  }
})
</script>

<template>
  <RouterView />
</template>

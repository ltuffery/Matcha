<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { Api } from './utils/api'
import { connectSocket } from '@/plugins/socket.js'
import NavBar from "@/components/NavBar.vue";
import {ref} from "vue";

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
</script>

<template>
  <NavBar v-if="isAuth" />
  <RouterView />
</template>

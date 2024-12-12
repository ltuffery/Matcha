<script setup>
import { RouterView } from 'vue-router'
import { isAuthenticated } from './services/auth'
import { socket } from './services/socket'

if (isAuthenticated()) {
  socket.emit('online')
}
</script>

<template>
  <RouterView />
</template>

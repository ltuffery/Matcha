<script setup>
import { computed, ref } from 'vue'
import { useOnlineUsersStore } from '@/store/onlineUsers.js'

const props = defineProps({
  type: {
    type: String,
    default: '',
  },
  username: {
    type: String,
    required: true,
  },
  src: {
    type: String,
    require: true,
    default:
      'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp',
  },
  width: {
    type: String,
    default: '16',
  },
  addedCss: {
    type: String,
    default: '',
  },
})

const isOnline = computed(() =>
  useOnlineUsersStore().isOnlineUser(props.username),
)
</script>

<template>
  <template v-if="props.type === 'squircle'">
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`mask mask-squircle w-${width}`, addedCss]">
        <img :src="src" />
      </div>
    </div>
  </template>

  <template v-else-if="props.type === 'r-full'">
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`rounded-full w-${width}`, addedCss]">
        <img :src="src" />
      </div>
    </div>
  </template>

  <template v-else>
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`w-${width}`, addedCss]">
        <img :src="src" />
      </div>
    </div>
  </template>
</template>

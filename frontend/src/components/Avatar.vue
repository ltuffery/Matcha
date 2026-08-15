<script setup lang="ts">
import { computed, ref } from 'vue'
import { useOnlineUsersStore } from '@/store/onlineUsers'

const props = defineProps<{
  type?: string
  username: string
  src?: string
  width?: string
  addedCss?: string
}>()

const isOnline = computed(() =>
  useOnlineUsersStore().isOnlineUser(props.username),
)
</script>

<template>
  <template v-if="props.type === 'squircle'">
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`mask mask-squircle w-${width ?? '16'}`, addedCss ?? '']">
        <img :src="src ?? 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'" alt="User avatar" />
      </div>
    </div>
  </template>

  <template v-else-if="props.type === 'r-full'">
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`rounded-full w-${width ?? '16'}`, addedCss ?? '']">
        <img :src="src ?? 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'" alt="User avatar" />
      </div>
    </div>
  </template>

  <template v-else>
    <div :class="['avatar', isOnline ? 'online' : 'offline']">
      <div :class="[`w-${width ?? '16'}`, addedCss ?? '']">
        <img :src="src ?? 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'" alt="User avatar" />
      </div>
    </div>
  </template>
</template>

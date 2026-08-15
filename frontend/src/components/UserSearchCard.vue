<script setup lang="ts">
import Avatar from '@/components/Avatar.vue'
import router from '@/router'

const props = defineProps<{
  username: string
  avatar?: string
}>()
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-6">
      <Avatar type="squircle" :username="props.username" :src="props.avatar" />
      <span class="text-2xl">{{ props.username }}</span>
    </div>
    <button
      @click="
        router.push({ name: 'profile', params: { username: props.username } })
      "
      class="btn"
    >
      Profile
    </button>
  </div>
</template>

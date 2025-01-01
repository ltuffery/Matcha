<script setup>
const props = defineProps({
  avatar: {
    type: String,
    default: 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp',
  },
  username: {
    type: String,
    required: true,
  },
  lastMessage: {
    type: String,
    required: true,
  }
})
</script>

<template>
  <div class="flex gap-4 items-center">
    <div class="avatar">
      <div class="mask mask-squircle w-16">
        <img
          :src="props.avatar"
          alt="Match avatar" />
      </div>
    </div>
    <div class="flex flex-col relative">
      <span class="font-bold">{{ props.username }}</span>
      <p class="truncate whitespace-nowrap overflow-hidden text-ellipsis text-sm">
        {{ props.lastMessage }}
      </p>
    </div>
  </div>
</template>

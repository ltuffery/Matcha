<script setup>
const props = defineProps({
  username: {
    type: String,
    required: true,
  },
  avatar: {
    type: String
  }
})
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-6">
      <div class="avatar">
        <div class="mask mask-squircle w-16">
          <img :src="props.avatar" />
        </div>
      </div>
      <span class="text-2xl">{{ props.username }}</span>
    </div>
    <button class="btn">Profile</button>
  </div>
</template>

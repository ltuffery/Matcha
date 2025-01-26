<script setup>
import Avatar from "@/components/Avatar.vue";

const props = defineProps({
  username: {
    type: String,
    required: true,
  },
  avatar: {
    type: String,
  },
})
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-6">
      <Avatar type="squircle" :username="props.username" :src="props.avatar" />
      <span class="text-2xl">{{ props.username }}</span>
    </div>
    <button class="btn">Profile</button>
  </div>
</template>

<script setup>
const props = defineProps({
  username: {
    type: String,
    required: true,
  }
})
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-6">
      <div class="avatar">
        <div class="mask mask-squircle w-16">
          <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
        </div>
      </div>
      <span class="text-2xl">{{ props.username }}</span>
    </div>
    <button class="btn">Profile</button>
  </div>
</template>
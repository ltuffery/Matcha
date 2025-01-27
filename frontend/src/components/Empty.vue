<script setup>
import router from '@/router/index.js'

const props = defineProps({
  text: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div class="flex flex-col gap-7 w-full h-full justify-center items-center">
    <span class="text-2xl">{{ props.text }}</span>
    <button
      @click="router.push({ name: 'main' })"
      class="btn btn-outline btn-primary"
    >
      On your matchs
    </button>
  </div>
</template>

<script setup lang="ts">
import router from '@/router'

const props = defineProps<{
  text: string
}>()
</script>

<template>
  <div class="flex flex-col gap-7 w-full h-full justify-center items-center">
    <span class="text-2xl">{{ props.text }}</span>
    <button
      @click="router.push({ name: 'browse' })"
      class="btn btn-outline btn-primary"
    >
      On your matchs
    </button>
  </div>
</template>

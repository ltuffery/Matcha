<script setup lang="ts">
const props = defineProps<{
  content: string
}>()
</script>

<template>
  <div
    class="badge badge-outline text-nowrap hover:bg-muted select-none cursor-pointer badge-lg"
  >
    {{ props.content }}
  </div>
</template>

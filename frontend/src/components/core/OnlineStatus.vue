<script setup lang="ts">
const props = defineProps<{
  isOnline: boolean
  showTextStatus?: boolean
}>()
</script>

<template>
  <span class="flex items-center gap-1">
    <span
      class="h-2 w-2 rounded-full"
      :class="props.isOnline ? 'bg-green-500' : 'bg-gray-400'"
    />
    <span v-if="props.showTextStatus">
      {{ props.isOnline ? 'En ligne' : 'Hors ligne' }}
    </span>
  </span>
</template>

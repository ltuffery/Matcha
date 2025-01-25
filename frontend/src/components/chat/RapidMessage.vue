<script setup>
const props = defineProps({
  content: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div
    class="badge badge-outline text-nowrap hover:bg-base-300 select-none cursor-pointer badge-lg"
  >
    {{ props.content }}
  </div>
</template>

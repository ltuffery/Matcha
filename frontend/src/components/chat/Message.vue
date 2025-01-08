<script setup>
const props = defineProps({
  message: {
    type: String,
    required: true,
  },
  'is-me': [Boolean]
})
</script>

<template>
  <div :class="{ 'justify-start': !props['isMe'], 'justify-end': props['isMe'], 'bg-primary': props['isMe'] }" class="flex p-3 rounded max-w-[40%] text-ellipsis overflow-auto overflow-wrap break-word">
    {{ props.message }}
  </div>
</template>

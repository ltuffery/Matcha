<script setup lang="ts">
const props = defineProps<{
  message: string
  isMe?: boolean
  tempo?: boolean
}>()
</script>

<style scoped>
.formatted-text {
  white-space: pre-line;
}
</style>

<template>
  <div
    v-if="!props.tempo"
    class="chat"
    :class="props.isMe ? 'chat-end' : 'chat-start'"
  >
    <div
      class="chat-bubble break-all formatted-text"
      :class="props.isMe ? 'chat-bubble-info' : 'chat-bubble-primary'"
    >
      {{ props.message }}
    </div>
  </div>
  <div
    v-else
    class="chat opacity-50"
    :class="props.isMe ? 'chat-end' : 'chat-start'"
  >
    <div class="chat-bubble break-all formatted-text chat-bubble-info">
      {{ props.message }}
    </div>
  </div>
</template>

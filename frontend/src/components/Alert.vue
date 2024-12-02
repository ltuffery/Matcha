<script setup>
import { defineProps, ref } from 'vue'

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div
    role="alert"
    class="alert"
    :class="{
      'alert-error': props.type == 'error',
    }"
  >
    <img alt="Alert icon" src="../assets/icons/close-circle-svgrepo-com.svg" class="h-8 w-8 shrink-0 stroke-current"/>
    <div>
      <span>{{ props.title }}</span>
    </div>
  </div>
</template>

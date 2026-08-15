<script setup lang="ts">
const emit = defineEmits<{
  input: [e: Event]
  'update:modelValue': [value: string]
}>()

const maxDate = setMaxDate()

function setMaxDate(): string {
  const maxDate = new Date()
  maxDate.setFullYear(new Date().getFullYear() - 18)

  const formattedDate = maxDate.toISOString().split('T')[0]
  return formattedDate as string
}

const emitChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  emit('input', e)
  emit('update:modelValue', target.value)
}
</script>

<template>
  <input
    class="input w-full input-bordered"
    type="date"
    :max="maxDate"
    @input="emitChange"
  />
</template>

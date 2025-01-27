<script setup>

const emit = defineEmits(['input', 'update:modelValue'])
const maxDate = setMaxDate()

function setMaxDate() {
  const maxDate = new Date()
  maxDate.setFullYear(new Date().getFullYear() - 18)

  const formattedDate = maxDate.toISOString().split('T')[0]
  return formattedDate
}// setMaxAge()

const emitChange = (e) => {
  emit('input', e)
  emit('update:modelValue', e.target.value)
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

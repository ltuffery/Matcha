<script setup lang="ts">
import {
  InputGroup,
  InputGroupAddon,
  InputGroupButton,
  InputGroupInput,
} from '@/components/ui/input-group'
import { Eye, EyeOff } from 'lucide-vue-next'
import { ref } from 'vue'

defineProps<{
  id?: string
  placeholder?: string
  invalid?: boolean
}>()
const model = defineModel<string>()

const visible = ref<boolean>(false)
</script>

<template>
  <InputGroup>
    <InputGroupInput
      :id="id ?? 'password'"
      v-model="model"
      :type="visible ? 'text' : 'password'"
      :placeholder="placeholder ?? 'Password'"
      :aria-invalid="invalid"
      autocomplete="current-password"
    />
    <InputGroupAddon align="inline-end">
      <InputGroupButton
        :aria-label="visible ? 'Hide password' : 'Show password'"
        :title="visible ? 'Hide password' : 'Show password'"
        size="icon-xs"
        @click="visible = !visible"
      >
        <Eye v-if="!visible" />
        <EyeOff v-if="visible" />
      </InputGroupButton>
    </InputGroupAddon>
  </InputGroup>
</template>

<script setup lang="ts">
import type { DateValue } from '@internationalized/date'
import { getLocalTimeZone, parseDate, today } from '@internationalized/date'
import { ChevronDownIcon } from '@lucide/vue'
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Label } from '@/components/ui/label'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'

const props = defineProps<{
  modelValue?: string
  error?: boolean
  class?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | undefined): void
}>()

const maxDate = today(getLocalTimeZone()).subtract({ years: 18 })
const minDate = today(getLocalTimeZone()).subtract({ years: 120 })

const date = computed<DateValue | undefined>(() => {
  if (!props.modelValue) return undefined
  try {
    return parseDate(props.modelValue)
  } catch {
    return undefined
  }
})

const label = computed(() =>
  date.value
    ? date.value.toDate(getLocalTimeZone()).toLocaleDateString()
    : 'Select date',
)

function onSelect(value: DateValue | undefined, close: () => void) {
  if (!value) return
  emit('update:modelValue', value.toString()) // "2000-05-12"
  close()
}
</script>

<template>
  <div class="flex flex-col gap-3">
    <Label for="date" class="px-1">Date of birth</Label>
    <Popover v-slot="{ close }">
      <PopoverTrigger as-child>
        <Button
          id="date"
          type="button"
          variant="outline"
          class="w-48 justify-between font-normal"
          :class="[props.class, error && 'border-destructive']"
          :aria-invalid="error"
        >
          {{ label }}
          <ChevronDownIcon />
        </Button>
      </PopoverTrigger>
      <PopoverContent align="start">
        <Calendar
          :model-value="date"
          :default-placeholder="maxDate"
          :max-value="maxDate"
          :min-value="minDate"
          layout="month-and-year"
          @update:model-value="v => onSelect(v as DateValue | undefined, close)"
        />
      </PopoverContent>
    </Popover>
  </div>
</template>

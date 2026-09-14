<script setup lang="ts">
import type { DateValue } from '@internationalized/date'
import { getLocalTimeZone, today } from '@internationalized/date'
import { ChevronDownIcon } from '@lucide/vue'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import { Label } from '@/components/ui/label'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { type Ref, ref } from 'vue'

const date = ref(today(getLocalTimeZone())) as Ref<DateValue>
const pops = defineProps({
  class: {
    required: false,
  },
})
</script>

<template>
  <div class="flex flex-col gap-3">
    <Label for="date" class="px-1"> Date of birth </Label>
    <Popover v-slot="{ close }">
      <PopoverTrigger as-child>
        <Button
          id="date"
          variant="outline"
          class="w-48 justify-between font-normal"
          :class="$props.class"
        >
          {{
            date
              ? date.toDate(getLocalTimeZone()).toLocaleDateString()
              : 'Select date'
          }}
          <ChevronDownIcon />
        </Button>
      </PopoverTrigger>
      <PopoverContent align="start">
        <Calendar
          :model-value="date"
          layout="month-and-year"
          @update:model-value="
            value => {
              if (value) {
                date = value
                close()
              }
            }
          "
        />
      </PopoverContent>
    </Popover>
  </div>
</template>

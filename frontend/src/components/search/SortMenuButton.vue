<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { ArrowUpDown } from '@lucide/vue'

export type Sort = 'year' | 'fame_rating' | 'localisation' | 'common_tags'

const SORT_LABELS: Record<Sort, string> = {
  year: 'Years',
  fame_rating: 'Fame rating',
  localisation: 'Distance',
  common_tags: 'Common tags',
}
const listSort = Object.keys(SORT_LABELS) as Sort[]

const selected = defineModel<Sort[]>({
  default: () => [],
})

const isChecked = (value: Sort) => selected.value.includes(value)

const toggle = (value: Sort) => {
  selected.value = isChecked(value)
    ? selected.value.filter(v => v !== value)
    : [...selected.value, value]
}

const priority = (value: Sort) => selected.value.indexOf(value) + 1

const totalSelected = computed(() => selected.value.length)

const reset = () => {
  selected.value = []
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="relative">
        <ArrowUpDown />
        <span
          v-if="totalSelected"
          class="absolute -top-1 -right-1 h-4 min-w-4 px-1 rounded-full bg-primary text-primary-foreground text-[10px] flex items-center justify-center"
        >
          {{ totalSelected }}
        </span>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="w-56" align="end">
      <DropdownMenuLabel>Sort by</DropdownMenuLabel>
      <DropdownMenuSeparator />

      <DropdownMenuCheckboxItem
        v-for="item in listSort"
        :key="item"
        :model-value="isChecked(item)"
        @update:model-value="toggle(item)"
        @select.prevent
      >
        {{ SORT_LABELS[item] }}
        <span
          v-if="isChecked(item)"
          class="ml-auto text-xs text-muted-foreground"
        >
          {{ priority(item) }}
        </span>
      </DropdownMenuCheckboxItem>

      <template v-if="totalSelected">
        <DropdownMenuSeparator />
        <DropdownMenuItem class="text-destructive" @click="reset">
          Reset
        </DropdownMenuItem>
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

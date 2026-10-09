<script setup lang="ts">
import { computed } from 'vue'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuPortal,
  DropdownMenuSeparator,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Funnel } from '@lucide/vue'

export interface FilterSubContent {
  label: string
  value: string
}

export interface FilterSubMenu {
  year: FilterSubContent[]
  localisation: FilterSubContent[]
  fame_rating: FilterSubContent[]
  tags: FilterSubContent[]
}

export type FilterKey = keyof FilterSubMenu
export type FilterSelection = Record<FilterKey, string[]>

const props = defineProps<{ filter: FilterSubMenu }>()

const selected = defineModel<FilterSelection>({
  default: () => ({ year: [], localisation: [], fame_rating: [], tags: [] }),
})

const LABELS: Record<FilterKey, string> = {
  year: 'Year',
  localisation: 'Localisation',
  fame_rating: 'Fame rating',
  tags: 'Tags',
}

const sections = computed(() =>
  (Object.keys(LABELS) as FilterKey[]).map(key => ({
    key,
    label: LABELS[key],
    items: props.filter[key] ?? [],
  })),
)

const isChecked = (key: FilterKey, value: string) =>
  selected.value[key].includes(value)

const toggle = (key: FilterKey, value: string) => {
  const current = selected.value[key]
  selected.value = {
    ...selected.value,
    [key]: current.includes(value)
      ? current.filter(v => v !== value)
      : [...current, value],
  }
}

const totalSelected = computed(() =>
  Object.values(selected.value).reduce((acc, arr) => acc + arr.length, 0),
)

const reset = () => {
  selected.value = { year: [], localisation: [], fame_rating: [], tags: [] }
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="relative">
        <Funnel />
        <span
          v-if="totalSelected"
          class="absolute -top-1 -right-1 h-4 min-w-4 px-1 rounded-full bg-primary text-primary-foreground text-[10px] flex items-center justify-center"
        >
          {{ totalSelected }}
        </span>
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="w-56" align="end">
      <DropdownMenuLabel>Filters</DropdownMenuLabel>
      <DropdownMenuSeparator />

      <DropdownMenuSub v-for="section in sections" :key="section.key">
        <DropdownMenuSubTrigger>
          {{ section.label }}
          <span
            v-if="selected[section.key].length"
            class="text-xs text-muted-foreground"
          >
            {{ selected[section.key].length }}
          </span>
        </DropdownMenuSubTrigger>

        <DropdownMenuPortal>
          <DropdownMenuSubContent class="max-h-72 overflow-y-auto">
            <DropdownMenuCheckboxItem
              v-for="item in section.items"
              :key="item.value"
              :model-value="isChecked(section.key, item.value)"
              @update:model-value="toggle(section.key, item.value)"
              @select.prevent
            >
              {{ item.label }}
            </DropdownMenuCheckboxItem>

            <DropdownMenuItem v-if="!section.items.length" disabled>
              None option
            </DropdownMenuItem>
          </DropdownMenuSubContent>
        </DropdownMenuPortal>
      </DropdownMenuSub>

      <template v-if="totalSelected">
        <DropdownMenuSeparator />
        <DropdownMenuItem class="text-destructive" @click="reset">
          Reset
        </DropdownMenuItem>
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

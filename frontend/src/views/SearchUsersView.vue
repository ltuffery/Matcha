<script setup lang="ts">
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { Card } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Slider } from '@/components/ui/slider'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { Separator } from '@/components/ui/separator'
import { Search, X, MapPin, TrendingUp, Cake, Tags, Check } from '@lucide/vue'
import FilterMenuButton, {
  type FilterSelection,
  type FilterSubContent,
  type FilterSubMenu,
} from '@/components/search/FilterMenuButton.vue'
import SortMenuButton, {
  type Sort,
} from '@/components/search/SortMenuButton.vue'
import type { Profile } from '@/api/users'
import MapLocator from '@/components/settings/sections/preferences/MapLocator.vue'
import { useSearch } from '@/composable/useSearch'

export interface SearchFilters {
  age: [number, number]
  fame: [number, number]
  location: string | null
  distance: number
  tags: string[]
}

const DEFAULTS: SearchFilters = {
  age: [18, 99],
  fame: [0, 100],
  location: null,
  distance: 50,
  tags: [],
}

const search = useSearch()

const filters = reactive<SearchFilters>(structuredClone(DEFAULTS))
const users = ref<Profile[]>([])

const hasActiveFilters = computed(
  () => JSON.stringify(filters) !== JSON.stringify(DEFAULTS),
)

const uniqueOptions = <T extends string | number>(
  values: T[],
  format: (v: T) => string = v => String(v),
): FilterSubContent[] =>
  [...new Set(values)]
    .sort((a, b) =>
      typeof a === 'number'
        ? a - (b as number)
        : String(a).localeCompare(String(b)),
    )
    .map(v => ({ label: format(v), value: String(v) }))

const subFilters = computed<FilterSubMenu>(() => ({
  year: uniqueOptions(
    users.value.map(u => u.age),
    t => `${t} years`,
  ),
  localisation: uniqueOptions(users.value.map(u => u.distance)),
  fame_rating: uniqueOptions(users.value.map(u => u.fame_rating)),
  tags: uniqueOptions(
    users.value.flatMap(u => u.tags),
    t => `#${t}`,
  ),
}))

const sorts = ref<Sort[]>([])
const subSelection = ref<FilterSelection>({
  year: [],
  localisation: [],
  fame_rating: [],
  tags: [],
})
const me = { city: 'Paris', tags: ['geek', 'sport'] }

const commonTags = (u: Profile) =>
  u.tags.filter(t => me.tags.includes(t)).length

const comparators: Record<Sort, (a: Profile, b: Profile) => number> = {
  year: (a, b) => a.age - b.age,
  fame_rating: (a, b) => b.fame_rating - a.fame_rating,
  localisation: (a, b) => a.distance - b.distance,
  common_tags: (a, b) => commonTags(b) - commonTags(a),
}

const matches = (selected: string[], value: string | number) =>
  selected.length === 0 || selected.includes(String(value))

const filteredUsers = computed(() =>
  users.value.filter(
    u =>
      matches(subSelection.value.year, u.age) &&
      matches(subSelection.value.localisation, u.distance) &&
      matches(subSelection.value.fame_rating, u.fame_rating) &&
      (subSelection.value.tags.length === 0 ||
        u.tags.some(t => subSelection.value.tags.includes(t))),
  ),
)

const sortedUsers = computed(() =>
  [...filteredUsers.value].sort((a, b) => {
    for (const s of sorts.value) {
      const diff = comparators[s](a, b)
      if (diff !== 0) return diff
    }
    return 0
  }),
)

// --- Actions ---
const toggleTag = (tag: string) => {
  const i = filters.tags.indexOf(tag)
  i === -1 ? filters.tags.push(tag) : filters.tags.splice(i, 1)
}

const resetFilters = () => Object.assign(filters, structuredClone(DEFAULTS))

let debounce: ReturnType<typeof setTimeout>
const fetchUsers = async () => {
  const data = await search.fetch({
    years: filters.age,
    fame_rating: filters.fame,
    distance: filters.distance,
    tags: filters.tags,
    sorts: sorts.value,
  })

  if (data === null || 'code' in data) {
    return
  }

  users.value = data
}

watch(
  filters,
  () => {
    clearTimeout(debounce)
    debounce = setTimeout(fetchUsers, 300)
  },
  { deep: true },
)

onMounted(fetchUsers)
</script>

<template>
  <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold tracking-tight">Search</h1>
      <p class="text-muted-foreground mt-1">Search all profile</p>
    </div>

    <div
      class="w-full flex items-center rounded-full border bg-background shadow-md hover:shadow-lg transition-shadow"
    >
      <!-- Year -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="ghost" size="lg" class="flex-1 rounded-l-full py-6">
            <Cake class="h-3.5 w-3.5" /> Year
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80" align="start">
          <p class="font-semibold mb-1">Tranche d'âge</p>
          <p class="text-sm text-muted-foreground mb-5">
            {{ filters.age[0] }} – {{ filters.age[1] }} ans
          </p>
          <Slider v-model="filters.age" :min="18" :max="99" :step="1" />
        </PopoverContent>
      </Popover>

      <Separator orientation="vertical" />

      <!-- Fame rating -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="ghost" size="lg" class="flex-1 rounded-none py-6">
            <TrendingUp class="h-3.5 w-3.5" /> Fame rating
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80">
          <p class="font-semibold mb-1">Fame rating</p>
          <p class="text-sm text-muted-foreground mb-5">
            Entre {{ filters.fame[0] }} et {{ filters.fame[1] }}
          </p>
          <Slider v-model="filters.fame" :min="0" :max="100" :step="5" />
        </PopoverContent>
      </Popover>

      <Separator orientation="vertical" />

      <!-- Localisation -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="ghost" size="lg" class="flex-1 rounded-none py-6">
            <MapPin class="h-3.5 w-3.5" /> Localisation
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80">
          <p class="font-semibold mb-3">Où ?</p>

          <MapLocator />

          <div class="mt-5">
            <p class="text-sm text-muted-foreground mb-4">
              Distance max : {{ filters.distance }} km
            </p>
            <Slider
              :model-value="[filters.distance]"
              @update:model-value="v => v && (filters.distance = v[0])"
              :min="5"
              :max="200"
              :step="5"
            />
          </div>
        </PopoverContent>
      </Popover>

      <Separator orientation="vertical" />

      <!-- Tags -->
      <Popover>
        <PopoverTrigger as-child>
          <Button variant="ghost" size="lg" class="flex-1 rounded-none py-6">
            <Tags class="h-3.5 w-3.5" /> Tags
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80" align="end">
          <p class="font-semibold mb-3">Centres d'intérêt</p>
          <div class="flex flex-wrap gap-2">
            <Badge
              v-for="tag in []"
              :key="tag"
              :variant="filters.tags.includes(tag) ? 'default' : 'outline'"
              class="cursor-pointer select-none px-3 py-1"
              @click="toggleTag(tag)"
            >
              <Check v-if="filters.tags.includes(tag)" class="h-3 w-3 mr-1" />
              #{{ tag }}
            </Badge>
          </div>
        </PopoverContent>
      </Popover>

      <!-- Bouton clear / search -->
      <div class="flex items-center pl-2">
        <Button
          v-if="hasActiveFilters"
          variant="ghost"
          size="icon"
          class="rounded-full h-11 w-11"
          title="Réinitialiser"
          @click="resetFilters"
        >
          <X class="h-4 w-4" />
        </Button>
        <Button size="icon" class="rounded-full h-11 w-11" @click="fetchUsers">
          <Search class="h-4 w-4" />
        </Button>
      </div>
    </div>

    <div
      v-if="!search.isLoading.value"
      class="flex justify-between items-center mt-6"
    >
      <p class="text-sm text-muted-foreground">
        {{ users.length }} profil{{ users.length > 1 ? 's' : '' }} trouvé{{
          users.length > 1 ? 's' : ''
        }}
      </p>

      <div>
        <FilterMenuButton v-model="subSelection" :filter="subFilters" />
        <SortMenuButton v-model="sorts" />
      </div>
    </div>

    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <template v-if="search.isLoading.value">
        <Card
          v-for="i in 6"
          :key="i"
          class="relative overflow-hidden p-0 gap-0 aspect-[3/4] border-0 shadow-md"
        >
          <Skeleton class="absolute inset-0 h-full w-full rounded-none" />

          <Skeleton
            class="absolute top-3 right-3 h-6 w-12 rounded-full bg-foreground/10"
          />

          <div class="absolute bottom-0 inset-x-0 p-4 flex flex-col gap-2">
            <Skeleton class="h-5 w-2/3 bg-foreground/10" />
            <Skeleton class="h-3 w-1/2 bg-foreground/10" />
            <div class="flex gap-1 mt-1">
              <Skeleton class="h-4 w-12 rounded-full bg-foreground/10" />
              <Skeleton class="h-4 w-10 rounded-full bg-foreground/10" />
              <Skeleton class="h-4 w-14 rounded-full bg-foreground/10" />
            </div>
          </div>
        </Card>
      </template>

      <template v-else-if="users.length">
        <Card
          v-for="u in sortedUsers"
          :key="u.username"
          class="relative overflow-hidden p-0 gap-0 aspect-[3/4] cursor-pointer border-0 shadow-md hover:shadow-xl"
        >
          <img
            :src="u.avatar"
            :alt="u.first_name"
            class="absolute inset-0 h-full w-full object-cover transition-transform duration-500"
          />

          <div
            class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"
          />
          <span
            class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-black/50 backdrop-blur px-2 py-1 text-xs"
          >
            <TrendingUp class="h-3 w-3" />
            {{ u.fame_rating }}
          </span>

          <div class="absolute bottom-0 flex flex-col w-full p-4 gap-4">
            <div class="inset-x-0">
              <p class="text-lg font-semibold">
                {{ u.first_name }}, {{ u.age }}
              </p>
              <p class="text-xs text-white/80 flex items-center gap-1">
                <MapPin class="h-3 w-3" /> {{ u.distance }} ·
                {{ u.distance }} km
              </p>
              <div class="flex flex-wrap gap-1 mt-2">
                <Badge
                  v-for="t in u.tags.slice(0, 6)"
                  :key="t"
                  variant="outline"
                >
                  #{{ t }}
                </Badge>
              </div>
            </div>

            <Button variant="outline" class="w-full">Voir le profile</Button>
          </div>
        </Card>
      </template>

      <div v-else class="col-span-full text-center text-muted-foreground py-10">
        Aucun profil ne correspond à ces critères
        <Button variant="link" @click="resetFilters"
          >Réinitialiser les filtres</Button
        >
      </div>
    </div>
  </div>
</template>

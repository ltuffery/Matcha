<script setup lang="ts">
import DoubleSlide from '@/components/DoubleSlide.vue'
import { onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { disconnect } from '@/services/auth'
import { Api } from '@/utils/api'
import countryCodes from '@/assets/countryCodes.json'
import { usePreferencesStore } from '@/store/preferences'
import { Tracking } from '@/services/tracking'
import type { CityInfo, GeoPositionInfo, Preferences } from '@/types'

import { Card, CardContent } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { Slider } from '@/components/ui/slider'
import { Switch } from '@/components/ui/switch'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'

const preferencesStore = usePreferencesStore()
const locationOpen = ref(false)
const locationQuery = ref('')

const preferences = ref<{
  age: { start: number; end: number }
  distance: number
  sexual_preference: string
  byTags: boolean
  pos: {
    lat: number
    lon: number
    is_custom_loc: boolean | number
    countryCode: string
    name: string
    posInfo: GeoPositionInfo | null
  }
  fame_rating: number
  cityList: CityInfo[] | null
}>({
  age: {
    start: preferencesStore.preferences.age_minimum,
    end: preferencesStore.preferences.age_maximum,
  },
  distance: preferencesStore.preferences.distance_maximum,
  sexual_preference: preferencesStore.preferences.sexual_preferences,
  byTags: preferencesStore.preferences.by_tags,
  pos: {
    lat: preferencesStore.preferences.lat,
    lon: preferencesStore.preferences.lon,
    is_custom_loc: preferencesStore.preferences.is_custom_loc,
    countryCode: 'FR',
    name: 'No Name',
    posInfo: null,
  },
  fame_rating: 10,
  cityList: null,
})

// Slider double (âge) : un seul ref [start, end]
const ageRange = ref<[number, number]>([
  preferences.value.age.start,
  preferences.value.age.end,
])

watchEffect(() => {
  preferences.value.age = {
    start: ageRange.value[0],
    end: ageRange.value[1],
  }
})

const distanceRange = ref<[number]>([preferences.value.distance])
watchEffect(() => {
  preferences.value.distance = distanceRange.value[0]
})

const fameRange = ref<[number]>([preferences.value.fame_rating])
watchEffect(() => {
  preferences.value.fame_rating = fameRange.value[0]
})

const refreshCityList = async (query: string) => {
  locationQuery.value = query
  preferences.value.cityList = (await Tracking.getCityListByName(
    query,
    preferences.value.pos.countryCode,
  )) as unknown as CityInfo[]
}

const selectCurrentLocation = () => {
  Tracking.setAtCurrentLocation()
  preferences.value.pos.is_custom_loc = false
  locationOpen.value = false
}

const selectCity = (city: CityInfo) => {
  preferences.value.pos.lat = city.lat
  preferences.value.pos.lon = city.lng
  preferences.value.pos.is_custom_loc = true
  preferences.value.pos.name = city.toponymName
  locationOpen.value = false
}

onMounted(async () => {
  preferences.value.pos.posInfo = await Tracking.getPositionInfoByLatLon(
    preferences.value.pos.lat,
    preferences.value.pos.lon,
  )
  preferences.value.pos.countryCode =
    preferences.value.pos.posInfo?.countryCode ??
    preferences.value.pos.countryCode
  preferences.value.pos.name =
    preferences.value.pos.posInfo?.name ?? preferences.value.pos.name
  preferences.value.cityList = await Tracking.getCityListByName(
    '',
    preferences.value.pos.countryCode,
  )
})

onUnmounted(async () => {
  if (localStorage.jwt == null) return
  const newObject: Preferences = {
    age_minimum: preferences.value.age.start,
    age_maximum: preferences.value.age.end,
    distance_maximum: preferences.value.distance,
    sexual_preferences: preferences.value.sexual_preference,
    by_tags: preferences.value.byTags,
    lat: preferences.value.pos.lat,
    lon: preferences.value.pos.lon,
    is_custom_loc: preferences.value.pos.is_custom_loc ? 1 : 0,
  }
  if (!preferencesStore.isChanged(newObject)) return
  const response = await Api.put('/users/me/preferences').send(
    newObject as unknown as Record<string, unknown>,
  )
  if (response.ok) preferencesStore.setPreferences(newObject)
})
</script>

<template>
  <div class="w-full flex flex-col gap-4">
    <!-- Tranche d'âge -->
    <Card>
      <CardContent class="flex flex-col gap-3 p-5">
        <div class="flex justify-between">
          <Label>Tranche d'âge</Label>
          <span class="text-sm text-muted-foreground">
            {{ preferences.age.start }} - {{ preferences.age.end }} ans
          </span>
        </div>
        <DoubleSlide
          v-model="ageRange"
          :min="18"
          :max="80"
          :start="preferences.age.start"
          :end="preferences.age.end"
        />
      </CardContent>
    </Card>

    <!-- Distance max -->
    <Card>
      <CardContent class="flex flex-col gap-3 p-5">
        <div class="flex justify-between">
          <Label>Distance maximum</Label>
          <span class="text-sm text-muted-foreground"
            >{{ preferences.distance }} km</span
          >
        </div>
        <Slider v-model="distanceRange" :min="5" :max="100" :step="1" />
      </CardContent>
    </Card>

    <!-- Fame rating -->
    <Card>
      <CardContent class="flex flex-col gap-3 p-5">
        <div class="flex justify-between">
          <Label>Fame rating maximum</Label>
          <span class="text-sm text-muted-foreground"
            >{{ preferences.fame_rating }} %</span
          >
        </div>
        <Slider v-model="fameRange" :min="0" :max="100" :step="1" />
      </CardContent>
    </Card>

    <!-- Préférence sexuelle -->
    <Card>
      <CardContent class="flex flex-col gap-3 p-5">
        <Label>Intéressé(e) par</Label>
        <Select v-model="preferences.sexual_preference">
          <SelectTrigger class="w-full">
            <SelectValue placeholder="Sélectionner" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="F">Femmes</SelectItem>
            <SelectItem value="M">Hommes</SelectItem>
            <SelectItem value="O">Autre</SelectItem>
            <SelectItem value="A">Tous</SelectItem>
          </SelectContent>
        </Select>
      </CardContent>
    </Card>

    <!-- Localisation -->
    <Card>
      <CardContent class="flex flex-col gap-3 p-5">
        <Label>Localisation</Label>
        <div class="flex gap-2 w-full">
          <Select
            v-model="preferences.pos.countryCode"
            @update:model-value="() => refreshCityList(locationQuery)"
          >
            <SelectTrigger class="w-24 text-center">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="code in countryCodes"
                :key="code"
                :value="code"
              >
                {{ code }}
              </SelectItem>
            </SelectContent>
          </Select>

          <Popover v-model:open="locationOpen">
            <PopoverTrigger as-child>
              <Input
                :model-value="
                  preferences.pos.is_custom_loc
                    ? preferences.pos.name
                    : 'Position actuelle'
                "
                readonly
                class="w-full cursor-pointer"
                @focus="locationOpen = true"
              />
            </PopoverTrigger>
            <PopoverContent class="w-[300px] p-2">
              <Input
                :model-value="locationQuery"
                placeholder="Rechercher une ville..."
                class="mb-2"
                @update:model-value="v => refreshCityList(String(v))"
              />
              <div class="max-h-60 overflow-y-auto flex flex-col">
                <button
                  class="text-left px-2 py-2 rounded hover:bg-muted text-sm"
                  @click="selectCurrentLocation"
                >
                  📍 Utiliser la position actuelle
                </button>
                <div class="h-px bg-border my-1" />
                <button
                  v-for="city in preferences.cityList"
                  :key="city.toponymName"
                  class="text-left px-2 py-2 rounded hover:bg-muted text-sm"
                  @click="selectCity(city)"
                >
                  {{ city.toponymName }}
                </button>
              </div>
            </PopoverContent>
          </Popover>
        </div>
      </CardContent>
    </Card>

    <!-- Recherche par tags -->
    <Card>
      <CardContent class="flex items-center justify-between p-5">
        <Label>Recherche par tags similaires</Label>
        <Switch v-model:checked="preferences.byTags" />
      </CardContent>
    </Card>

    <!-- Déconnexion -->
    <Card class="mt-6">
      <CardContent class="p-5">
        <Button variant="outline" class="w-full" @click="disconnect">
          Déconnexion
        </Button>
      </CardContent>
    </Card>
  </div>
</template>

<script setup lang="ts">
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Label } from '@/components/ui/label'
import { Slider } from '@/components/ui/slider'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { computed, onMounted, ref } from 'vue'
import MapLocator, {
  type Location,
} from '@/components/settings/sections/preferences/MapLocator.vue'
import { MapPin } from 'lucide-vue-next'
import { usePreferences } from '@/composable/usePreferences'
import type { SexualPreference, UserPreferences } from '@/api/preferences'
import { Switch } from '@/components/ui/switch'

const preference = usePreferences()

const pref = ref<UserPreferences>({
  distance_maximum: 50,
  age_minimum: 18,
  age_maximum: 27,
  by_tags: false,
  sexual_preferences: 'A',
  lat: 50,
  lon: 11,
  is_custom_loc: false,
})

const distanceRange = computed({
  get: () => [pref.value.distance_maximum],
  set: ([v]) => (pref.value.distance_maximum = v),
})

const ageRange = computed({
  get: () => [pref.value.age_minimum, pref.value.age_maximum],
  set: ([min, max]) => {
    pref.value.age_minimum = min
    pref.value.age_maximum = max
  },
})

const sexualPref = computed({
  get: () => pref.value.sexual_preferences,
  set: v => (pref.value.sexual_preferences = v as SexualPreference),
})

const locations = computed<Location | null>({
  get: () =>
    ({ lat: pref.value.lat, lng: pref.value.lon, city: 'Paris' }) as Location,
  set: loc => {
    if (!loc) return
    pref.value.lat = loc.lat
    pref.value.lon = loc.lng
    pref.value.is_custom_loc = true
    city.value = loc.city ?? ''
  },
})
const city = ref('')
const fameRatingRange = ref([100])
const byTags = computed({
  get: () => pref.value.by_tags,
  set: v => {
    pref.value.by_tags = v
  },
})

const saved = ref(false)

onMounted(async () => {
  pref.value = await preference.fetchPreference()
})

async function save() {
  saved.value = false
  await preference.updatePreferences(pref.value)
  saved.value = true
}
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Search Preferences</CardTitle>
      <CardDescription>
        Set the criteria for your profile suggestions.
      </CardDescription>
    </CardHeader>
    <CardContent class="space-y-8">
      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Maximum distance</Label>
          <Badge variant="secondary">{{ distanceRange[0] }} km</Badge>
        </div>
        <Slider v-model="distanceRange" :min="1" :max="100" :step="5" />
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Age group</Label>
          <Badge variant="secondary"
            >{{ ageRange[0] }} - {{ ageRange[1] }} ans</Badge
          >
        </div>
        <Slider v-model="ageRange" :min="18" :max="60" :step="1" />
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Fame rating gap</Label>
          <Badge variant="secondary">{{ fameRatingRange[0] }}</Badge>
        </div>
        <Slider v-model="fameRatingRange" :min="0" :max="1000" :step="1" />
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Interested by</Label>
        </div>
        <Select v-model="sexualPref">
          <SelectTrigger class="w-55">
            <SelectValue placeholder="Sexual Preference" />
          </SelectTrigger>

          <SelectContent>
            <SelectGroup>
              <SelectLabel>Sexual Preference</SelectLabel>
              <SelectItem value="W"> Women </SelectItem>
              <SelectItem value="M"> Man </SelectItem>
              <SelectItem value="O"> Other </SelectItem>
              <SelectItem value="A"> All </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>

      <div class="flex items-center space-x-2">
        <Switch v-model="byTags" />
        <Label>Use tags correspondence</Label>
      </div>

      <div class="space-y-3">
        <div class="flex items-center justify-between text-sm">
          <Label>Localisation</Label>
          <span
            v-if="locations?.city"
            class="flex items-center gap-1 text-muted-foreground"
          >
            <MapPin class="h-3 w-3" /> {{ locations.city }}
          </span>
        </div>
        <MapLocator
          :distance_max="distanceRange[0] * 1000"
          v-model="locations"
        />
      </div>

      <div class="flex justify-end">
        <Button :disabled="saved" @click="() => save()">Save change</Button>
      </div>
    </CardContent>
  </Card>
</template>

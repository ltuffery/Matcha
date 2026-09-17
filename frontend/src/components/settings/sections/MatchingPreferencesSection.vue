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
import {
  Map,
  MapMarker,
  MarkerContent,
  MarkerPopup,
  MarkerTooltip,
} from '@/components/ui/map'
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
import { TagsInput, TagsInputInput } from '@/components/ui/tags-input'
import { Badge } from '@/components/ui/badge'
import { ref } from 'vue'

const distanceRange = ref([50])
const ageRange = ref([22, 35])
const fameRatingRange = ref([100])

const locations = [
  { id: 1, name: 'Empire State Building', lng: -73.9857, lat: 40.7484 },
  { id: 2, name: 'Central Park', lng: -73.9654, lat: 40.7829 },
  { id: 3, name: 'Times Square', lng: -73.9855, lat: 40.758 },
]
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
        <Slider v-model="distanceRange" :max="200" :step="5" />
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
          <Label>Famerating</Label>
          <Badge variant="secondary">{{ fameRatingRange[0] }}</Badge>
        </div>
        <Slider v-model="fameRatingRange" :min="0" :max="1000" :step="1" />
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Interested by</Label>
        </div>
        <Select>
          <SelectTrigger class="w-55">
            <SelectValue placeholder="Sexual Preference" />
          </SelectTrigger>

          <SelectContent>
            <SelectGroup>
              <SelectLabel>Sexual Preference</SelectLabel>
              <SelectItem value="women"> Women </SelectItem>
              <SelectItem value="man"> Man </SelectItem>
              <SelectItem value="other"> Other </SelectItem>
              <SelectItem value="all"> All </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Tags</Label>
        </div>

        <TagsInput class="w-75">
          <TagsInputInput placeholder="Tags..." />
        </TagsInput>
      </div>

      <div class="space-y-3">
        <div class="flex justify-between text-sm">
          <Label>Localisation</Label>
        </div>

        <div class="h-80 overflow-hidden rounded-lg border">
          <Map :center="[-74.006, 40.7128]" :zoom="11">
            <MapMarker
              v-for="loc in locations"
              :key="loc.id"
              :longitude="loc.lng"
              :latitude="loc.lat"
            >
              <MarkerContent>
                <div
                  class="bg-primary size-4 rounded-full border-2 border-white shadow-lg"
                />
              </MarkerContent>
              <MarkerTooltip>{{ loc.name }}</MarkerTooltip>
              <MarkerPopup>
                <div class="space-y-1">
                  <p class="text-foreground font-medium">
                    {{ loc.name }}
                  </p>
                  <p class="text-muted-foreground text-xs">
                    {{ loc.lat.toFixed(4) }}, {{ loc.lng.toFixed(4) }}
                  </p>
                </div>
              </MarkerPopup>
            </MapMarker>
          </Map>
        </div>
      </div>

      <div class="flex justify-end">
        <Button>Save change</Button>
      </div>
    </CardContent>
  </Card>
</template>

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
import { TagsInput, TagsInputInput } from '@/components/ui/tags-input'
import { Badge } from '@/components/ui/badge'
import { onMounted, ref } from 'vue'
import MapLocator, {
  type Location,
} from '@/components/settings/sections/preferences/MapLocator.vue'

const distanceRange = ref([50])
const ageRange = ref([22, 35])
const fameRatingRange = ref([100])
const locations = ref<Location | null>(null)

onMounted(() => {})
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
        <MapLocator v-model="locations" />
      </div>

      <div class="flex justify-end">
        <Button>Save change</Button>
      </div>
    </CardContent>
  </Card>
</template>

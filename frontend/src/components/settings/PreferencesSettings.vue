<script setup lang="ts">
import DoubleSlide from '@/components/DoubleSlide.vue'
import { onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { disconnect } from '@/services/auth'
import { Api } from '@/utils/api'
import countryCodes from '@/assets/countryCodes.json'
import { usePreferencesStore } from '@/store/preferences'
import { Tracking } from '@/services/tracking'
import type { CityInfo, GeoPositionInfo, Preferences } from '@/types'

const preferencesStore = usePreferencesStore()
const ageRange = ref<{ t1: number; t2: number }>()
const dropdownLocation = ref<HTMLInputElement>()
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

const refreshCityList = (e: Event) => {
  const target = e.target as HTMLInputElement
  preferences.value.cityList = Tracking.getCityListByName(
    target.value,
    preferences.value.pos.countryCode,
  ) as unknown as CityInfo[]
}

const selectCityHandler = (e: Event) => {
  const target = e.target as HTMLElement
  if (target.getAttribute('gcl') != null) {
    Tracking.setAtCurrentLocation()
    preferences.value.pos.is_custom_loc = false
  } else {
    preferences.value.pos.lat = Number(target.getAttribute('lat'))
    preferences.value.pos.lon = Number(target.getAttribute('lon'))
    preferences.value.pos.is_custom_loc = true
    if (dropdownLocation.value) dropdownLocation.value.value = target.innerText
    ;(document.activeElement as HTMLElement | null)?.blur()
  }
}

watchEffect(() => {
  if (ageRange.value) {
    preferences.value.age = {
      start: ageRange.value.t1,
      end: ageRange.value.t2,
    }
  }
})

onMounted(async () => {
  preferences.value.pos.posInfo = await Tracking.getPositionInfoByLatLon(
    preferences.value.pos.lat,
    preferences.value.pos.lon,
  )
  preferences.value.pos.countryCode = preferences.value.pos.posInfo?.countryCode
    ? preferences.value.pos.posInfo.countryCode
    : preferences.value.pos.countryCode
  preferences.value.pos.name = preferences.value.pos.posInfo?.name
    ? preferences.value.pos.posInfo.name
    : preferences.value.pos.name
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
  const response = await Api.put('/users/me/preferences').send(newObject as unknown as Record<string, unknown>)
  if (response.ok) preferencesStore.setPreferences(newObject)
})
</script>

<template>
  <div class="w-full flex items-center flex-col">
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Age range :</label>
        <label
          >{{ preferences.age.start }} - {{ preferences.age.end }} years</label
        >
      </div>
      <DoubleSlide
        v-model="ageRange"
        :min="18"
        :max="80"
        :start="preferences.age.start"
        :end="preferences.age.end"
      />
    </div>
  </div>

  <div>
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Maximum distance :</label>
        <label>{{ preferences.distance }} Km</label>
      </div>
      <input
        v-model="preferences.distance"
        type="range"
        min="5"
        max="100"
        class="range"
      />
    </div>
  </div>

  <div>
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Maximum fame rating :</label>
        <label>{{ preferences.fame_rating }} %</label>
      </div>
      <input
        v-model="preferences.fame_rating"
        type="range"
        min="0"
        max="100"
        class="range"
      />
    </div>
  </div>

  <div>
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Interested by :</label>
      </div>
      <select
        v-model="preferences.sexual_preference"
        class="select select-bordered w-full"
      >
        <option :selected="preferences.sexual_preference === 'F'" value="F">
          Women
        </option>
        <option :selected="preferences.sexual_preference === 'M'" value="M">
          Man
        </option>
        <option :selected="preferences.sexual_preference === 'O'" value="O">
          Other
        </option>
        <option :selected="preferences.sexual_preference === 'A'" value="A">
          All
        </option>
      </select>
    </div>
  </div>

  <div>
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Location :</label>
      </div>

      <div class="flex gap-2 w-full">
        <select
          class="select select-bordered bg-none text-center text-lg p-0 w-20"
          @change="refreshCityList"
          v-model="preferences.pos.countryCode"
        >
          <option
            v-for="(code, index) in countryCodes"
            :key="index"
            class="hover:bg-muted cursor-pointer"
            :selected="code === preferences.pos.countryCode"
          >
            {{ code }}
          </option>
        </select>

        <div class="dropdown w-full">
          <input
            ref="dropdownLocation"
            tabindex="1"
            role="button"
            class="input input-bordered w-full"
            :placeholder="
              preferences.pos.is_custom_loc
                ? preferences.pos.name
                : 'Current Location'
            "
            @input="refreshCityList"
          />
          <div
            tabindex="1"
            class="dropdown-content card card-compact bg-muted z-[1] w-full max-h-60 overflow-y-auto p-2 shadow"
          >
            <div
              class="my-3 hover:bg-muted cursor-pointer"
              @click="selectCityHandler"
              gcl
            >
              Get current location
            </div>
            <div class="divider my-0 mb-3"></div>
            <div
              v-for="(city, index) in preferences.cityList"
              @click="selectCityHandler"
              :key="index"
              :lat="city.lat"
              :lon="city.lng"
              class="hover:bg-muted cursor-pointer"
            >
              {{ city.toponymName }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="card bg-muted gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Research by same tags :</label>
        <input
          v-model="preferences.byTags"
          type="checkbox"
          class="toggle"
          :checked="preferences.byTags"
        />
      </div>
    </div>
  </div>

  <div>
    <div class="card w-full p-5 mt-10">
      <button @click="disconnect" class="btn btn-outline">Disconnect</button>
    </div>
  </div>
</template>

<style scoped></style>

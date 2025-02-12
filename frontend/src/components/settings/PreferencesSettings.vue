<script setup>
import DoubleSlide from '@/components/DoubleSlide.vue'
import { onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { disconnect } from '@/services/auth'
import { Api } from '@/utils/api.js'
import countrieCodes from '@/assets/countrieCodes.json'

const props = defineProps({
  preferences: {
    type: Object,
    required: true,
  },
})

const preferences = ref({
  age: {
    start: props.preferences.age_minimum,
    end: props.preferences.age_maximum,
  },
  distance: props.preferences.distance_maximum,
  sexual_preference: props.preferences.sexual_preferences,
  pos: {
    lat: props.preferences.lat,
    lon: props.preferences.lon,
    user_set_loc: props.preferences.user_set_loc,
    countryCode: 'FR',
    name: 'No Name',
    posInfo: null,
  },
  fame_rating: 10,
  cityList: null
})

const ageRange = ref()
const byTags = ref(!!props.preferences.by_tags)

const getPositionInfoByLatLon = async () => {
  let res = await fetch(
    `http://api.geonames.org/findNearbyJSON?lat=${preferences.value.pos.lat}&lng=${preferences.value.pos.lon}&username=example`,
  )
  res = await res.json()
  res = {
    countryCode: res.geonames[0].countryCode,
    name: res.geonames[0].toponymName,
    countryCode: res.geonames[0].countryCode,
  }
  return res
}

const getPositionInfoByName = async (city, countryCode) => {
  let res = await fetch(`http://api.geonames.org/searchJSON?name_startsWith=${city}&country=${countryCode}&featureClass=P&maxRows=10&username=example`)
  res = await res.json()
  return (res.geonames)
}

const refreshCityList = async (e) => {
  preferences.value.cityList = await getPositionInfoByName(e.target.value, preferences.value.pos.countryCode)
}

onMounted(async () => {
  preferences.value.pos.posInfo = await getPositionInfoByLatLon()
  preferences.value.pos.countryCode = preferences.value.pos.posInfo?.countryCode ? preferences.value.pos.posInfo.countryCode : preferences.value.pos.countryCode
  preferences.value.pos.name = preferences.value.pos.posInfo?.name ? preferences.value.pos.posInfo.name : preferences.value.pos.name
  preferences.value.cityList = await getPositionInfoByName("", preferences.value.pos.countryCode)
})

watchEffect(() => {
  if (ageRange.value) {
    preferences.value.age = {
      start: ageRange.value.t1,
      end: ageRange.value.t2,
    }
  }
})

onUnmounted(() => {
  Api.put('/users/me/preferences').send({
    age_minimum: preferences.value.age.start,
    age_maximum: preferences.value.age.end,
    distance_maximum: preferences.value.distance,
    sexual_preferences: preferences.value.sexual_preference,
    by_tags: byTags.value,
  })
})
</script>

<template>
  <div class="w-full flex items-center flex-col">
    <div class="card bg-base-300 gap-3 w-full p-5">
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
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Maximum distance :</label>
        <label>{{ preferences.distance }} Km</label>
      </div>
      <input
        v-model="preferences.distance"
        type="range"
        min="5"
        max="100"
        value="preferences.distance"
        class="range"
      />
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Maximum fame rating :</label>
        <label>{{ preferences.fame_rating }} %</label>
      </div>
      <input
        v-model="preferences.fame_rating"
        type="range"
        min="0"
        max="100"
        value="preferences.fame_rating"
        class="range"
      />
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Interested by :</label>
      </div>
      <select class="select select-bordered w-full">
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
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Location :</label>
      </div>

      <div class="flex gap-2 w-full">
        <select class="select select-bordered bg-none text-center text-lg p-0 w-20" @change="refreshCityList" v-model="preferences.pos.countryCode">
          <option
            v-for="(code, index) in countrieCodes"
            :key="index"
            class="hover:bg-base-300 cursor-pointer"
            :selected="code === preferences.pos.countryCode"
          >
            {{ code }}
          </option>
        </select>

        <div class="dropdown w-full">
          <input
            tabindex="1"
            role="button"
            class="input input-bordered w-full"
            :placeholder="preferences.pos.user_set_loc ? preferences.pos.name : 'Current Location'"
            @input="refreshCityList"
          />
          <div
            tabindex="1"
            class="dropdown-content card card-compact bg-base-200 z-[1] w-full max-h-60 overflow-y-auto p-2 shadow"
          >
            <div class="my-3 hover:bg-base-300 cursor-pointer">Get current location</div>
            <div class="divider my-0 mb-3"></div>
            <div v-for="(city, index) in preferences.cityList" :key="index" :countryCode="city.countryCode" class="hover:bg-base-300 cursor-pointer">
              {{ city.toponymName }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Research by same tags :</label>
        <input
          v-model="byTags"
          type="checkbox"
          class="toggle"
          :checked="byTags"
        />
      </div>
      <!--            <TagSelector class="max-h-72 overflow-y-auto" />-->
    </div>
  </div>

  <div>
    <div class="card w-full p-5 mt-10">
      <button @click="disconnect" class="btn btn-outline">Disconnect</button>
    </div>
  </div>
</template>

<style scoped>

</style>

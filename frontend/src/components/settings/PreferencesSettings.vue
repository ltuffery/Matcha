<script setup>
import DoubleSlide from '@/components/DoubleSlide.vue'
import { onUnmounted, ref, watchEffect } from 'vue'
import { disconect } from '@/services/auth'
import { Api } from '@/utils/api.js'

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
  fame_rating: 10
})

const ageRange = ref()
const byTags = ref(!!props.preferences.by_tags)

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
      <button @click="disconect" class="btn btn-outline">Disconect</button>
    </div>
  </div>
</template>

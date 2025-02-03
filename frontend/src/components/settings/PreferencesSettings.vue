<script setup>
import DoubleSlide from '@/components/DoubleSlide.vue'
import { onUnmounted, ref, watchEffect } from 'vue'
import { disconect } from '@/services/auth';

const preferences = ref({
  age: {
    start: 18,
    end: 30,
  },
  distance: 20,
  sexual_preference: 'F',
})

const ageRange = ref()
watchEffect(() => {
  if (ageRange.value) {
    preferences.value.age = {
      start: ageRange.value.t1,
      end: ageRange.value.t2,
    }
  }
})

onUnmounted(() => {
  console.log('Save in DB ? (check if modif)')
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
        <label>Interested by :</label>
      </div>
      <select class="select select-bordered w-full max-w-xs">
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
        <input type="checkbox" class="toggle" checked="checked" />
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

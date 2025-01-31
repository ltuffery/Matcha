<script setup>
import { Api } from '@/utils/api.js'
import { onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css' // Import Swiper styles
import { EffectCreative } from 'swiper/modules'
import {useRoute} from "vue-router";

const modules = ref([EffectCreative]);
let data = ref()
const route = useRoute()

const getData = async () => {
  const res = await Api.get(`users/${route.params.username}`).send()
  data.value = await res.json()
}

const formatGender = (type) => {
  switch (type) {
    case 'M':
      return "Man"
    case 'F':
      return "Woman"
    case 'O':
      return "Other"
  }
}
// await getOui()
onMounted(async () => {
  await getData()
})
</script>

<template>
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl">
      <div class="flex flex-col p-4 items-center gap-4 w-full h-full">
        <div class="h-[40%] w-full">
          <swiper
            :slides-per-view="1"
            :space-between="10"
            :loop="false"
            :watchOverflow="true"
            :effect="'creative'"
            :creativeEffect="{
              prev: {
                shadow: true,
                translate: [0, 0, -400],
              },
              next: {
                translate: ['100%', 0, 0],
              },
            }"
            :modules="modules"
            class="w-full h-full rounded-box z-10"
          >
            <swiper-slide v-for="(image, index) in data?.photos" :key="index">
              <img
                :src="image"
                alt="Slide image"
                class="w-full h-full rounded-box object-cover"
              />
            </swiper-slide>
          </swiper>
        </div>

        <div class="flex flex-col w-full gap-2">
          <div class="flex justify-between">
            <div
              class="flex gap-2 bg-base-300 rounded-box p-2 px-3 w-fit text-xl"
            >
              <div>
                <label class="font-semibold">{{ data?.first_name }}</label>
                <label>,</label>
              </div>
              <label class="">{{ data?.age }}</label>
            </div>
            <div class="badge badge-neutral">{{ formatGender(data?.gender) }}</div>
          </div>
          <div
            class="flex gap-1 bg-base-300 rounded-box p-2 px-3 w-fit text-xl"
          >
            <label>At </label>
            <label class="font-semibold">{{ data?.distance }} km</label>
            <label>from you</label>
          </div>
        </div>

        <div class="w-full">
          <div class="flex flex-col gap-1 bg-base-300 rounded-box p-2 px-3">
            <label class="text-base">Biography</label>
            <div
              class="flex text-wrap overflow-y-auto bg-base-200/70 rounded-box p-2 px-3 w-full min-h-24 max-h-72 text-lg"
            >
              <label>{{ data?.biography }}</label>
            </div>
          </div>
        </div>

        <div class="w-full">
          <div class="flex flex-col gap-1 bg-base-300 rounded-box p-2 px-3">
            <label class="text-base">Passions</label>
            <div
              class="flex text-wrap overflow-y-auto bg-base-200/70 rounded-box p-2 px-3 w-full min-h-24 max-h-72 text-lg"
            >
              <div>
                <div
                  v-for="tag in data?.tags"
                  :key="tag"
                  class="badge badge-outline badge-lg gap-1 bg-base-100"
                >
                  {{ tag }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-2 w-full mt-8">
          <button class="btn btn-outline w-full">Block user</button>
          <button class="btn btn-outline btn-error w-full">
            Report profile
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

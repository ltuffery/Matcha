<script setup>
import { Api } from '@/utils/api.js'
import { onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css' // Import Swiper styles
import { EffectCreative } from 'swiper/modules'
import { useRoute } from 'vue-router'
import router from '@/router/index.js'
import {getSocket} from "@/plugins/socket.js";

const modules = ref([EffectCreative])
const data = ref()
const notFound = ref(false)
const route = useRoute()

const getData = async () => {
  const res = await Api.get(`users/${route.params.username}`).send()

  if (res.ok) data.value = await res.json()
  else notFound.value = true
}

const formatGender = type => {
  switch (type) {
    case 'M':
      return 'Man'
    case 'F':
      return 'Woman'
    case 'O':
      return 'Other'
  }
}
onMounted(async () => {
  await getData()

  const decodedToken = JSON.parse(atob(localStorage.jwt.split('.')[1]))

  if (notFound.value === false && route.params.username !== decodedToken.username)
    getSocket().emit("view", route.params.username)
})
</script>

<template>
  <!-- User not found -->
  <div
    v-if="notFound"
    class="relative h-full w-full flex justify-center items-center"
  >
    <div class="flex justify-center flex-col gap-4">
      <h1 class="text-3xl">User not found !</h1>
      <button
        @click="router.push({ name: 'main' })"
        class="btn btn-primary btn-outline"
      >
        Go home
      </button>
    </div>
  </div>

  <!-- User information if is found -->
  <div v-else class="flex flex-col p-4 items-center gap-4 w-full h-full">
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
        <div class="flex gap-2 bg-base-300 rounded-box p-2 px-3 w-fit text-xl">
          <div>
            <label class="font-semibold">{{ data?.first_name }}</label>
            <label>,</label>
          </div>
          <label class="">{{ data?.age }}</label>
        </div>
        <div class="badge badge-neutral">
          {{ formatGender(data?.gender) }}
        </div>
      </div>
      <div class="flex gap-1 bg-base-300 rounded-box p-2 px-3 w-fit text-xl">
        <label>At </label>
        <label class="font-semibold"
          >{{ data?.distance === -1 ? 'less of 1' : data?.distance }} km</label
        >
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
      <button class="btn btn-outline btn-error w-full">Report profile</button>
    </div>
  </div>

  <button
    v-if="data?.me"
    class="sticky bottom-[5%] left-full mr-10 z-30 rounded-full btn btn-outline bg-base-300/80 flex items-center justify-center size-14"
  >
    <svg
      class="w-11"
      viewBox="0 0 24 24"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
      <g
        id="SVGRepo_tracerCarrier"
        stroke-linecap="round"
        stroke-linejoin="round"
      ></g>
      <g id="SVGRepo_iconCarrier">
        <path
          d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
        ></path>
      </g>
    </svg>
  </button>
</template>

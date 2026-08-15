<script setup lang="ts">
import MainUser from '@/components/MainUser.vue'
import { onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'
import { getSocket } from '@/plugins/socket'
import Filter from '@/components/main/Filter.vue'
import { EffectCreative } from 'swiper/modules'
import type { Swiper as SwiperType } from 'swiper/types'
import type { User } from '@/types'

const swiperRef = ref(null)
const swiperInstance = ref<SwiperType | null>(null)
const sections = ref<User[]>([])
const skeleton = ref(true)
const modules = ref([EffectCreative])

const onSwiperInit = (swiper: SwiperType) => {
  swiperInstance.value = swiper
}

const onSlideChange = () => {
  getSocket().emit('browsing')
}

const goToNextSlide = () => {
  if (swiperInstance.value) {
    swiperInstance.value.slideNext()
  } else {
    console.log("Swiper instance n'est pas encore prête.")
  }
}

getSocket().on('browsing', (user: User) => {
  if (user !== null) {
    sections.value.push(user)
    skeleton.value = false
  }
})

onMounted(() => {
  getSocket().emit('browsing')
  getSocket().emit('browsing')
})
</script>

<template>
  <div class="h-full w-full" v-if="skeleton">
    <div class="h-[85%] w-full skeleton"></div>
    <div>
      <div class="skeleton h-10 w-2/6 mt-5"></div>
      <div class="skeleton w-full h-5 mt-1"></div>
    </div>
  </div>

  <div v-else class="h-full w-full flex justify-center items-center">
    <Filter class="absolute top-2 right-2 z-20" />

    <div class="w-full h-full rounded-lg max-w-lg">
      <swiper
        ref="swiperRef"
        :direction="'vertical'"
        :slides-per-view="1"
        :loop="false"
        :pagination="{ clickable: true }"
        :watchOverflow="true"
        :effect="'creative'"
        :creativeEffect="{
          prev: {
            shadow: true,
            translate: [0, '-20%', -1],
          },
          next: {
            translate: [0, '100%', 0],
          },
        }"
        :modules="modules"
        class="w-full h-full"
        @swiper="onSwiperInit"
        @slideChange="onSlideChange"
      >
        <swiper-slide
          v-for="(content, index) in sections"
          :key="index"
          class="flex items-center justify-center h-full bg-gray-100"
        >
          <div class="bg-muted h-full shadow-lg">
            <MainUser @nextSlide="goToNextSlide" :user="content" />
          </div>
        </swiper-slide>
      </swiper>
    </div>
  </div>
</template>

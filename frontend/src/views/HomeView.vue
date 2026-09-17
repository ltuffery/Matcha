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
const sections = ref<User[]>([
  {
    username: 'test',
    biography: 'dsfsdfsdfsd',
    age: 22,
    photos: [
      'https://images.unsplash.com/photo-1643023234393-776f2624061b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8OHwxMTU0MDcyNnx8ZW58MHx8fHx8',
      'https://images.unsplash.com/photo-1552134378-71b4a8333430?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8MTR8MTE1NDA3MjZ8fGVufDB8fHx8fA%3D%3D',
    ],
  },
  {
    username: 'test2',
    biography: 'sgojdgiofjg',
    age: 30,
    photos: [
      'https://images.unsplash.com/photo-1718749861351-918524751a6b?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
      'https://images.unsplash.com/photo-1701163802834-46d90f8b452a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxjb2xsZWN0aW9uLXBhZ2V8M3wxMTU0MDcyNnx8ZW58MHx8fHx8',
      'https://images.unsplash.com/photo-1645441261871-e468b99a8d7c?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    ],
  },
])
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
//
// getSocket().on('browsing', (user: User) => {
//   if (user !== null) {
//     sections.value.push(user)
//     skeleton.value = false
//   }
// })

onMounted(() => {
  // getSocket().emit('browsing')
  // getSocket().emit('browsing')
})
</script>

<template>
  <div
    class="flex w-full justify-center overflow-hidden rounded-lg py-4"
  >
    <div class="relative h-full w-full max-w-lg">
      <Swiper
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
        class="h-full! w-full! rounded-lg"
        @swiper="onSwiperInit"
        @slideChange="onSlideChange"
      >
        <SwiperSlide
          v-for="(content, index) in sections"
          :key="index"
          class="h-full! flex! items-center justify-center rounded-lg"
        >
          <MainUser
            class="h-full w-full"
            @nextSlide="goToNextSlide"
            :user="content"
          />
        </SwiperSlide>
      </Swiper>
    </div>
  </div>
</template>

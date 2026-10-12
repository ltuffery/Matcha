<script setup lang="ts">
import MainUser from '@/components/MainUser.vue'
import { onMounted, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'
import { getSocket } from '@/plugins/socket'
import Filter from '@/components/main/Filter.vue'
import { EffectCreative } from 'swiper/modules'
import type { Swiper as SwiperType } from 'swiper/types'
import type { Profile } from '@/api/users'
import { useUsers } from '@/composable/useUsers'
import SortDropdown from '@/components/SortDropdown.vue'

const users = useUsers()

const swiperInstance = ref<SwiperType | null>(null)

const skeleton = ref(true)
const modules = ref([EffectCreative])

const onSwiperInit = (swiper: SwiperType) => {
  swiperInstance.value = swiper
}

const onSlideChange = () => {
  // getSocket().emit('browsing')
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

onMounted(async () => {
  // getSocket().emit('browsing')
  // getSocket().emit('browsing')

  await users.suggestion({limit: 5, order: 'desc', sort: "age"})
})
</script>

<template>
  <div class="flex w-full justify-center overflow-hidden rounded-lg py-4">
    <div class="relative h-full w-full max-w-lg">
      <SortDropdown/>

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
          v-for="(content, index) in users.suggestedProfiles.value"
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

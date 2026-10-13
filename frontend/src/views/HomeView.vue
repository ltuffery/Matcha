<script setup lang="ts">
import MainUser from '@/components/MainUser.vue'
import { onMounted, ref, nextTick } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'
import { getSocket } from '@/plugins/socket'
import Filter from '@/components/main/Filter.vue'
import { EffectCreative } from 'swiper/modules'
import type { Swiper as SwiperType } from 'swiper/types'
import type { Profile, SuggestionParams } from '@/api/users'
import { useUsers } from '@/composable/useUsers'
import SortDropdown from '@/components/SortDropdown.vue'

const users = useUsers()

const swiperInstance = ref<SwiperType | null>(null)
const MAX_PROFILES = 50
const PRELOAD_THRESHOLD = 0
const params: SuggestionParams = { limit: 5 }

const skeleton = ref(true)
const modules = ref([EffectCreative])

const onSwiperInit = (swiper: SwiperType) => {
  swiperInstance.value = swiper
}

const loadMore = async () => {
  if (!users.hasMore.value) return
  console.log("Je charge nouveau copain")
  const swiper = swiperInstance.value

  params.offset = swiper!.activeIndex;
  await users.suggestion(params)

  // On supprime les plus anciens si on dépasse le max
  const list = users.suggestedProfiles.value
  if (list.length > MAX_PROFILES) {
    const removed = list.length - MAX_PROFILES
    list.splice(0, removed)

    // Les slides ont changé de position : on recale Swiper sans animation
    // await nextTick()

    // if (swiper) {
    //   swiper.update()
    //   swiper.slideTo(swiper.activeIndex - removed, 0, false)
    // }
  }
}

const onSlideChange = (swiper: SwiperType) => {
  if (swiper.activeIndex > swiper.previousIndex) {
      for (let i = swiper.previousIndex; i < swiper.activeIndex; i++) {
        const profile = users.suggestedProfiles.value[i]
        if (profile) users.pass(profile.username)
      }
    }

    // 2. Préchargement
    const remaining = users.suggestedProfiles.value.length - 1 - swiper.activeIndex
    if (remaining <= PRELOAD_THRESHOLD) loadMore()
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

  await users.suggestion(params)
})
</script>

<template>
  <div class="flex w-full justify-center overflow-hidden rounded-lg py-4">
    <div class="relative h-full w-full max-w-lg">
      <SortDropdown/>

      <Swiper
        v-if="users.suggestedProfiles.value.length"
        :observer="true"
        :observe-parents="true"
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
          v-for="content in users.suggestedProfiles.value"
          :key="content.username"
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

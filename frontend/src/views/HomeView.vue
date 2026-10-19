<script setup lang="ts">
import MainUser from '@/components/MainUser.vue'
import { onMounted, ref, watch } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'
import { EffectCreative } from 'swiper/modules'
import type { Swiper as SwiperType } from 'swiper/types'
import type { Profile, SuggestionParams } from '@/api/users'
import { useUsers } from '@/composable/useUsers'
import SortDropdown from '@/components/SortDropdown.vue'

const users = useUsers()

const usedSort = ref<'age' | 'distance' | 'fame' | 'common_tags' | null>(null)
const order = ref<'asc' | 'desc'>('asc')
const buildParams = (): SuggestionParams => ({
  limit: 5,
  ...(usedSort.value && { sort: usedSort.value }),
  ...(usedSort.value && { order: order.value }),
})

const swiperInstance = ref<SwiperType | null>(null)
const MAX_PROFILES = 50
const PRELOAD_THRESHOLD = 0

const modules = ref([EffectCreative])

const onSwiperInit = (swiper: SwiperType) => {
  swiperInstance.value = swiper
}

const loadMore = async () => {
  if (!users.hasMore.value) return
  await users.suggestion(buildParams())

  // Delete old profiles (if > max)
  const list = users.suggestedProfiles.value
  if (list.length > MAX_PROFILES) {
    const removed = list.length - MAX_PROFILES
    list.splice(0, removed)
  }
  users.suggestedProfiles.value = list
}

const onSlideChange = (swiper: SwiperType) => {
  if (swiper.activeIndex > swiper.previousIndex) {
      for (let i = swiper.previousIndex; i < swiper.activeIndex; i++) {
        const profile = users.suggestedProfiles.value[i]
        if (profile) users.pass(profile.username)
      }
    }

    const remaining = users.suggestedProfiles.value.length - 1 - swiper.activeIndex
    if (remaining <= PRELOAD_THRESHOLD) loadMore()
}

const goToNextSlide = () => {
  if (swiperInstance.value) {
    swiperInstance.value.slideNext()
  }
}

watch([usedSort, order], async ([newSort], [oldSort]) => {
  if (!newSort && !oldSort) return
  users.suggestionReset()
  await users.suggestion(buildParams())
  swiperInstance.value?.slideTo(0, 0, false)
})

onMounted(async () => {
  await users.suggestion(buildParams())
})
</script>

<template>
  <div class="flex w-full justify-center overflow-hidden rounded-lg py-4">
    <div class="relative h-full w-full max-w-lg">
      <SortDropdown v-model:sort="usedSort" v-model:order="order"/>

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
      <div v-else class="h-full! flex! items-center justify-center rounded-lg bg-gray-200/5">
        <div class="flex flex-col">
          <span class="text-xl">Plus de profil disponible 😖</span>
          <span>Modifie tes preferences et/ou filtres</span>
        </div>
      </div>
    </div>
  </div>
</template>

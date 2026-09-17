<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue'
import vDoubleTap from '@/directives/doubleTap'
import { ref, computed } from 'vue'
import 'swiper/swiper-bundle.css'
import { Api } from '@/utils/api'
import { EffectCreative, Pagination } from 'swiper/modules'
import ReportModal from '@/components/report/ReportModal.vue'
import { Heart, X, MapPin, BadgeCheck, ChevronUp } from 'lucide-vue-next'
import { Skeleton } from '@/components/ui/skeleton'
import { Badge } from '@/components/ui/badge'
import type { User } from '@/types'
import { Button } from '@/components/ui/button'

const props = defineProps<{
  user: User
}>()

const isLiked = ref(false)
const animated = ref(false)
const rejectAnimated = ref(false)
const skeleton = ref(false)
const activeSlide = ref(0)
const modules = ref([EffectCreative, Pagination])

const emit = defineEmits<{
  nextSlide: []
}>()

const photosCount = computed(() => props.user.photos?.length ?? 0)

function likeUser() {
  isLiked.value = true
  animated.value = true

  Api.post(`/users/${props.user.username}/like`).send()

  setTimeout(() => {
    animated.value = false
    emit('nextSlide')
  }, 350)
}

function rejectUser() {
  rejectAnimated.value = true

  setTimeout(() => {
    rejectAnimated.value = false
    emit('nextSlide')
  }, 350)
}

function btnLike() {
  isLiked.value = !isLiked.value

  if (isLiked.value) {
    animated.value = true
    Api.post(`/users/${props.user.username}/like`).send()
    setTimeout(() => {
      animated.value = false
      emit('nextSlide')
    }, 350)
  } else {
    Api.delete(`/users/${props.user.username}/unlike`).send()
  }
}
</script>

<template>
  <!-- SKELETON -->
  <div
    v-if="skeleton"
    class="relative h-full w-full overflow-hidden rounded-lg shadow-lg"
  >
    <Skeleton class="absolute inset-0 h-full w-full" />
    <div
      class="absolute bottom-0 left-0 z-20 flex w-full flex-col gap-2 bg-linear-to-t from-black/70 via-black/20 to-transparent p-5"
    >
      <Skeleton class="h-8 w-2/5 rounded-md" />
      <Skeleton class="h-4 w-4/5 rounded-md" />
      <Skeleton class="h-4 w-3/5 rounded-md" />
    </div>
  </div>

  <div
    v-else
    class="group relative h-full w-full select-none overflow-hidden rounded-lg bg-zinc-900 shadow-2xl ring-1 ring-black/10"
  >
    <swiper
      :slides-per-view="1"
      :loop="false"
      :watchOverflow="true"
      :effect="'creative'"
      :creativeEffect="{
        prev: { shadow: true, translate: [0, 0, -400] },
        next: { translate: ['100%', 0, 0] },
      }"
      :modules="modules"
      class="absolute inset-0 z-10 h-full w-full rounded-lg"
      @slideChange="s => (activeSlide = s.activeIndex)"
    >
      <swiper-slide
        v-for="(image, index) in props.user.photos"
        :key="index"
        class="h-full! w-full!"
      >
        <img
          :src="image"
          alt="Photo de profil"
          class="h-full w-full object-cover rounded-lg"
          v-double-tap="likeUser"
        />
      </swiper-slide>
    </swiper>

    <div class="absolute inset-x-3 top-3 z-30 flex gap-1.5">
      <div
        v-for="(_, index) in photosCount"
        :key="index"
        class="h-1 flex-1 overflow-hidden rounded-full bg-white/30"
      >
        <div
          class="h-full rounded-full bg-white transition-all duration-300"
          :class="index <= activeSlide ? 'w-full' : 'w-0'"
        />
      </div>
    </div>

    <div
      class="pointer-events-none absolute inset-x-0 bottom-0 z-10 h-2/3 bg-linear-to-t from-black/90 via-black/40 to-transparent"
    />

    <div
      class="absolute bottom-28 left-1/2 z-20 flex -translate-x-1/2 flex-col items-center text-white/70 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
    >
      <ChevronUp class="size-6 animate-bounce" />
      <span class="text-[11px] font-medium tracking-wide">More profile</span>
    </div>

    <div class="absolute bottom-0 left-0 z-20 w-full p-5 pb-24 text-white">
      <div class="flex items-baseline gap-2">
        <h2 class="truncate text-5xl font-bold tracking-tight drop-shadow-sm">
          {{ props.user.username }}
        </h2>
        <span v-if="props.user.age" class="text-xl font-light text-white/85">
          {{ props.user.age }}
        </span>
      </div>
      <p
        class="mt-1.5 line-clamp-2 text-sm leading-snug text-white/90 drop-shadow-sm"
      >
        {{ props.user.biography }}
      </p>
    </div>

    <div
      class="absolute inset-x-0 bottom-4 z-30 flex items-center justify-center gap-12"
    >
      <Button
        type="button"
        @click="rejectUser"
        aria-label="Passer"
        class="flex size-12 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white shadow-lg backdrop-blur-xl transition-all duration-200 hover:scale-110 hover:bg-white/20 active:scale-90"
      >
        <X class="size-6" />
      </Button>

      <Button
        type="button"
        @click="btnLike"
        aria-label="Aimer"
        class="flex size-18 items-center justify-center rounded-full text-white shadow-xl transition-all duration-200 hover:scale-110 active:scale-90"
        :class="
          isLiked
            ? 'bg-linear-to-br from-rose-500 to-pink-600'
            : 'bg-linear-to-br from-rose-400 to-fuchsia-500'
        "
      >
        <Heart class="size-8" :fill="isLiked ? 'currentColor' : 'none'" />
      </Button>

      <ReportModal :username="props.user.username" />
    </div>

    <Heart
      class="pointer-events-none absolute left-1/2 top-1/2 z-40 size-20 -translate-x-1/2 -translate-y-1/2 text-rose-500 drop-shadow-2xl transition-all duration-500 ease-out"
      :class="animated ? 'scale-100 opacity-90' : 'scale-50 opacity-0'"
      fill="currentColor"
    />

    <X
      class="pointer-events-none absolute left-1/2 top-1/2 z-40 size-20 -translate-x-1/2 -translate-y-1/2 text-zinc-300 drop-shadow-2xl transition-all duration-500 ease-out"
      :class="rejectAnimated ? 'scale-100 opacity-90' : 'scale-50 opacity-0'"
    />
  </div>
</template>

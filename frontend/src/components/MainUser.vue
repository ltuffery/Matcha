<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import vDoubleTap from '@/directives/doubleTap.js'
import { ref } from 'vue'
import 'swiper/swiper-bundle.css'
import { Api } from '@/utils/api.js'
import { EffectCreative } from 'swiper/modules'

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})

const isLiked = ref(false)
const animated = ref(false)
const skeleton = ref(false)
const modules = ref([EffectCreative])

const emit = defineEmits(['nextSlide'])

function likeUser(event) {
  isLiked.value = true
  animated.value = true

  Api.post(`/users/${props.user.username}/like`).send()

  setTimeout(() => {
    animated.value = false
    emit('nextSlide')
  }, 300)
}

function btnLike(event) {
  isLiked.value = !isLiked.value

  if (isLiked.value) {
    Api.post(`/users/${props.user.username}/like`).send()
    emit('nextSlide')
  } else {
    Api.delete(`/users/${props.user.username}/unlike`).send()
  }
}
</script>

<template>
  <!-- ### Skeleton part ### -->
  <skeleton v-if="skeleton === true">
    <div class="w-full rounded-lg static">
      <div
        class="skeleton w-full h-full shadow-md absolute left-0 start-0 z-10"
      ></div>

      <div
        class="absolute left-0 bottom-0 w-full bg-gradient-to-b from-base-100 to-base-200 flex flex-col items-start z-20"
      >
        <div class="p-2 w-full relative">
          <div class="flex items-center">
            <span class="skeleton h-10 w-2/6"></span>
          </div>
          <p class="skeleton w-full h-5 mt-1"></p>
        </div>
      </div>
    </div>
  </skeleton>

  <!-- ### main part part ### -->
  <div v-if="skeleton === false" class="h-full w-full rounded-lg static">
    <swiper
      :slides-per-view="1"
      :loop="false"
      :pagination="{ clickable: true }"
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
      class="w-full h-full absolute left-0 start-0 z-10"
    >
      <swiper-slide v-for="(image, index) in props.user.photos" :key="index">
        <img
          :src="image"
          alt="Slide image"
          class="w-full h-full rounded-box object-cover"
          v-double-tap="likeUser"
        />
      </swiper-slide>
    </swiper>
    <div
      class="absolute left-0 bottom-0 w-full text-black bg-gradient-to-b from-transparent to-base-200 flex flex-col items-start z-20"
    >
      <div class="p-2 w-full relative">
        <img
          src="../assets/icons/arrow-up.svg"
          alt="like btn"
          class="absolute inset-x-[47%] mr-5 bottom-2/3 size-14 cursor-pointer"
        />
        <div class="flex items-center">
          <span
            class="text-4xl whitespace-nowrap overflow-hidden text-zinc-100"
          >
            {{ props.user.username }}
          </span>
        </div>
        <p
          class="truncate whitespace-nowrap overflow-hidden text-ellipsis text-sm text-zinc-100 mt-1"
        >
          {{ props.user.biography }}
        </p>
      </div>
    </div>
    <div class="absolute right-0 bottom-20 text-black m-2 z-20">
      <img
        :src="
          isLiked
            ? '/src/assets/icons/heart-broken.svg'
            : '/src/assets/icons/heart.svg'
        "
        @click="btnLike"
        alt="like btn"
        class="size-10 my-2 cursor-pointer"
      />
      <img
        src="../assets/icons/flag.svg"
        alt="more option btn"
        class="size-10 my-2 cursor-pointer"
      />
    </div>
    <img
      src="../assets/icons/heart.svg"
      class="absolute inset-x-1/2 inset-y-1/2 z-20 size-10 transition-transform duration-500"
      :class="[animated ? 'scale-[15] opacity-100' : 'scale-100 opacity-0']"
      alt=""
    />
  </div>
</template>

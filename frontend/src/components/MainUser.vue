<template>
  <!-- ### Skeleton part ### -->
  <skeleton v-if="skeleton == true">
    <div class="carousel w-full rounded-lg static">
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
  <div v-if="skeleton == false" class="carousel w-full rounded-lg static">
    <swiper
      :slides-per-view="1"
      :space-between="10"
      :loop="false"
      :pagination="{ clickable: true }"
      :watchOverflow="true"
      class="w-full h-full bg-gray-200 shadow-md absolute left-0 start-0 z-10"
    >
      <swiper-slide v-for="(image, index) in images" :key="index">
        <img
          :src="image"
          alt="Slide image"
          class="w-full h-full object-cover"
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
            >{{ name }}</span
          >
          <img
            src="../assets/checked.png"
            alt="profile verified"
            class="size-7 mx-2"
          />
        </div>
        <p
          class="truncate whitespace-nowrap overflow-hidden text-ellipsis text-sm text-zinc-100 mt-1"
        >
          Mini bio en non wrap
          sdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfsdfdssdfsdfsdfsdfsdfsdfsdfsdfdsfsdfsdfsdfsdfsdfdsf
        </p>
      </div>
    </div>
    <div class="absolute right-0 bottom-20 text-black m-2 z-20">
      <img
        :src="
          isLiked ? '/src/assets/icons/heart-broken.svg' : '/src/assets/icons/heart.svg'
        "
        @click="btnlike"
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
    />
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/swiper-bundle.css'
import doubleTap from '../directives/doubleTap.js'

export default {
  props: {
    name: String,
  },
  components: {
    Swiper,
    SwiperSlide,
  },
  directives: {
    doubleTap,
  },
  data() {
    return {
      images: [
        'src/assets/cat.gif',
        'https://via.placeholder.com/600x400?text=Image+2',
        'https://via.placeholder.com/600x400?text=Image+3',
      ],
      isLiked: false,
      animated: false,
      skeleton: false,
    }
  },
  methods: {
    likeUser(event) {
      console.log('This user is liked: ', this.name)
      this.isLiked = true
      this.animated = true
      setTimeout(() => {
        this.animated = false
        this.$emit('nextSlide')
      }, 300)
    },
    btnlike(event) {
      this.isLiked = !this.isLiked
      if (this.isLiked) this.$emit('nextSlide')
    },
  },
}
</script>

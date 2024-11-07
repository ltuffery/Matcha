<template>
  <div class="carousel w-full rounded-lg static">
    <swiper
      :slides-per-view="1"
      :space-between="10"
      :loop="false"
      :pagination="{ clickable: true }"
      :watchOverflow="true"
      class="w-full h-full bg-gray-200 shadow-md absolute left-0 start-0 z-10"
    >
      <swiper-slide v-for="(image, index) in images" :key="index">
        <img :src="image" alt="Slide image" class="w-full h-full object-cover" v-double-tap="likeUser" />
      </swiper-slide>

      <div class="swiper-pagination"></div>
    </swiper>
    <div class="absolute left-0 bottom-0 w-full text-black bg-neutral-content/40 flex flex-col items-start z-20">
      <div class="m-2">
        <div class="flex items-center">
          <span class="text-4xl whitespace-nowrap overflow-hidden">Display Name</span>
          <img src="../images/checked.png" alt="profile verified" class="size-7 mx-2" />
        </div>
        <p class="truncate whitespace-nowrap overflow-hidden text-ellipsis text-sm mt-1">Mini bio en non wrap</p>
      </div>
    </div>
    <div class="absolute right-0 bottom-20 text-black m-2 z-20">
      <img :src="isLiked ? '/src/images/heart-broken.svg' : '/src/images/heart.svg'" @click="dislike" alt="like btn" class="size-10 my-2" />
      <!-- <img src="../images/user.svg" alt="like btn" class="size-10 my-2" /> -->
      <img src="../images/double-arrow-up.svg" alt="like btn" class="size-10 my-2" />
    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css"; // Import Swiper styles
import doubleTap from '../directives/doubleTap.js';

export default {
  props: {
    name: String
  },
  components: {
    Swiper,
    SwiperSlide,
  },
  directives: {
    doubleTap, // Enregistrement de la directive
  },
  data() {
    return {
      images: [
        "../../cat.gif",
        "https://via.placeholder.com/600x400?text=Image+2",
        "https://via.placeholder.com/600x400?text=Image+3",
      ],
      isLiked: false,
    };
  },
  methods: {
    likeUser(event) {
      console.log("This user is liked: ", this.name);
      this.isLiked = true;
    },
    dislike(event)
    {
      this.isLiked = false;
    }
  },
};
</script>
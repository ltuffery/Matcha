<template>
  <div class="carousel w-full static">
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
    <div class="absolute left-0 bottom-0 z-20">
      <span>
        Display Name
      </span>
      <p class="truncate whitespace-nowrap overflow-hidden text-ellipsis">Mini bio en non wrap</p>
    </div>
    <div class="absolute right-0 bottom-0 z-20">
      Les boutons !
    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css"; // Import Swiper styles
import doubleTap from '../directives/doubleTap.js';

let username;

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
        "https://via.placeholder.com/600x400?text=Image+1",
        "https://via.placeholder.com/600x400?text=Image+2",
        "https://via.placeholder.com/600x400?text=Image+3",
      ],
    };
  },
  setup(props) {
    username = props.name;
  },
  methods: {
    likeUser(event) {
      console.log("This user is liked: ", username);
    }
  },
};
</script>
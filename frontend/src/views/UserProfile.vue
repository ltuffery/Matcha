<template>
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl">
        <swiper
          :slides-per-view="1"
          :space-between="0"
          :loop="true"
          :pagination="{ clickable: true }"
          class="w-full h-full absolute"
        >
          <swiper-slide v-for="(image, index) in images" :key="index">
            <img :src="image" alt="Slide image" class="w-full h-full object-cover" />
          </swiper-slide>

          <div class="swiper-pagination"></div>
        </swiper>
    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css"; // Import Swiper styles

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      images: [
        'https://as1.ftcdn.net/v2/jpg/06/12/64/52/1000_F_612645270_KBcBTf5CToMDyGr1hDpqSNyMK6eaXrPq.jpg',
        'http://as1.ftcdn.net/v2/jpg/09/25/29/78/1000_F_925297895_12R4VyhRAEmvMGHEdGSXXj3B9PTHVYUa.jpg',
        'https://as1.ftcdn.net/v2/jpg/02/44/38/08/1000_F_244380850_H3xd2rrb9CfCIcTyFcepVL670vvuTA0b.jpg',
      ],
    };
  },
};
</script>

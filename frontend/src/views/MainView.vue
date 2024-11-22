<script setup>
import MainUser from '../components/MainUser.vue';
import { ref } from 'vue';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

const swiperRef = ref(null);
const swiperInstance = ref(null);


const onSwiperInit = (swiper) => {
  swiperInstance.value = swiper;
};

const goToNextSlide = () => {
  if (swiperInstance.value) {
    swiperInstance.value.slideNext();
  } else {
    console.log("Swiper instance n'est pas encore prête.");
  }
};
</script>


<template>
<!-- ### Skeleton part ### -->
<skeleton v-if="skeleton">
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200">
    <div class="card bg-base-100 w-full max-w-lg shrink-0 shadow-2xl">
        
      <div class="carousel w-full rounded-lg static">

        <div class="skeleton w-full h-full shadow-md absolute left-0 start-0 z-10">
        </div>

        <div class="absolute left-0 bottom-0 w-full bg-gradient-to-b from-base-100 to-base-200 flex flex-col items-start z-20">
          <div class="p-2 w-full relative">
            <div class="flex items-center">
              <span class="skeleton h-10 w-2/6"></span>
            </div>
            <p class="skeleton w-full h-5 mt-1"></p>
          </div>
        </div>
      </div>

    </div>
  </main>
</skeleton>



<!-- ### main part part ### -->
<main v-if="skeleton == false" class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200">
  <div class="card bg-base-100  w-full max-w-lg shrink-0 shadow-2xl">
    <div class="carousel w-full h-screen rounded-lg">
      <swiper
        ref="swiperRef"
        :direction="'vertical'"
        :slides-per-view="1"
        :space-between="10"
        :loop="false"
        :pagination="{ clickable: true }"
        :watchOverflow="true"
        class="w-full h-full"
        @swiper="onSwiperInit">

        <swiper-slide v-for="(content, index) in sections" :key="index" class="flex items-center justify-center h-full bg-gray-100">
          <div class="bg-base-200 shadow-lg text-center max-w-lg">
            <component :is="MainUser" @nextSlide="goToNextSlide" :name="content" />
          </div>
        </swiper-slide>

        <div class="swiper-pagination"></div>
      </swiper>
    </div>
  </div>
</main>
</template>

<script>
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      sections: [
          "userID1",
          "userID2",
          "userID3",
      ],

      skeleton: false,
    };
  },
};
</script>
<style>
.carousel {
  height: 100vh;
  max-height: 70rem;
}
</style>
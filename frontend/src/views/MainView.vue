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

 <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200">
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
      @swiper="onSwiperInit"
    >
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
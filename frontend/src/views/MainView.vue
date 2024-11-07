<!-- <template>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200">
    <div class="card bg-base-100 h-screen w-full max-w-lg shrink-0 shadow-2xl">
      coucou
    </div>
  </main>
</template> -->







<!-- <template>
  <div class="carousel w-full">
    <swiper
      :slides-per-view="1"
      :space-between="10"
      :loop="true"
      :pagination="{ clickable: true }"
      class="w-full h-64 bg-gray-200 rounded-lg shadow-md"
    >
      <swiper-slide v-for="(image, index) in images" :key="index">
        <img :src="image" alt="Slide image" class="w-full h-full object-cover rounded-lg" />
      </swiper-slide>

      <div class="swiper-pagination"></div>
    </swiper>
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
        "https://via.placeholder.com/600x400?text=Image+1",
        "https://via.placeholder.com/600x400?text=Image+2",
        "https://via.placeholder.com/600x400?text=Image+3",
      ],
    };
  },
};
</script> -->





<script setup>
import MainUser from '../components/MainUser.vue'
</script>


<template>

 <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200">
    <div class="card bg-base-100 h-screen w-full max-w-lg shrink-0 shadow-2xl">

  <div class="carousel w-full h-screen">
    <swiper
      :direction="'vertical'"
      :slides-per-view="1"
      :space-between="10"
      :loop="false"
      :pagination="{ clickable: true }"
      :watchOverflow="true"
      class="w-full h-full"
    >
      <!-- Div différente pour chaque slide -->
      <swiper-slide v-for="(content, index) in sections" :key="index" class="flex items-center justify-center h-full bg-gray-100">
        <div class="bg-white rounded-lg shadow-lg text-center max-w-lg">
          <component :is="MainUser" :name="content" />
        </div>
      </swiper-slide>

      <!-- Pagination Swiper -->
      <div class="swiper-pagination"></div>
    </swiper>
  </div>
  </div>
  </main>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

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
/* Style additionnel pour ajuster la taille si nécessaire */
.carousel {
  height: 100vh; /* Pour que chaque slide prenne toute la hauteur de l'écran */
}
</style>
<script setup>
import MainUser from '../components/MainUser.vue';
import { ref } from 'vue';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

// Créer une référence pour accéder aux méthodes de Swiper
const swiperRef = ref(null);
const swiperInstance = ref(null); // Définir swiperInstance comme une référence Vue

// Fonction pour initialiser swiperInstance lorsqu'il est prêt
const onSwiperInit = (swiper) => {
  swiperInstance.value = swiper; // Stocker l'instance Swiper dans swiperInstance
};

// Fonction pour aller au slide suivant
const goToNextSlide = () => {
  if (swiperInstance.value) { // Accéder à l'instance via swiperInstance.value
    swiperInstance.value.slideNext(); // Passe au slide suivant
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
      <!-- Div différente pour chaque slide -->
      <swiper-slide v-for="(content, index) in sections" :key="index" class="flex items-center justify-center h-full bg-gray-100">
        <div class="bg-base-200 shadow-lg text-center max-w-lg">
          <component :is="MainUser" @nextSlide="goToNextSlide" :name="content" />
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
// import { Swiper, SwiperSlide } from "swiper/vue";
// import "swiper/swiper-bundle.css";

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
  max-height: 70rem;
}
</style>
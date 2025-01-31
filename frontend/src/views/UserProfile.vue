<template>
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl">

      <div class="flex flex-col p-4 items-center gap-4 w-full h-full">
        <div class="h-[40%] w-full ">
          <swiper
            :slides-per-view="1"
            :space-between="10"
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
            class="w-full h-full shadow-md rounded-box z-10"
          >
            <swiper-slide v-for="(image, index) in images" :key="index">
              <img
                :src="image"
                alt="Slide image"
                class="w-full h-full object-cover"
              />
            </swiper-slide>
          </swiper>
        </div>

        <div class="flex flex-col w-full gap-2">
          <div class="flex justify-between">
            <div class="flex gap-2 bg-base-300 rounded-box p-2 px-3 w-fit text-xl">
              <div>
                <label class="font-semibold">First Name</label>
                <label>,</label>
              </div>
              <label class="">24</label>
            </div>
            <div class="badge badge-neutral">Woman</div>
          </div>
          <div class="flex gap-1 bg-base-300 rounded-box p-2 px-3 w-fit text-xl">
            <label>At </label>
            <label class="font-semibold">24 km</label>
            <label>from you</label>
          </div>
        </div>


        <div class="w-full">
          <div class="flex flex-col gap-1 bg-base-300 rounded-box p-2 px-3">
            <label class="text-base">Biography</label>
            <div class="flex text-wrap overflow-y-auto bg-base-200/70 rounded-box p-2 px-3 w-full min-h-24 max-h-72 text-lg">
              <label>Biography content</label>
            </div>
          </div>
        </div>

        <div class="w-full">
          <div class="flex flex-col gap-1 bg-base-300 rounded-box p-2 px-3">
            <label class="text-base">Passions</label>
            <div class="flex text-wrap overflow-y-auto bg-base-200/70 rounded-box p-2 px-3 w-full min-h-24 max-h-72 text-lg">
              <label>All Tags</label>
            </div>
          </div>
        </div>



        <div class="flex flex-col gap-2 w-full mt-8">
          <button class="btn btn-outline w-full">Block user</button>
          <button class="btn btn-outline btn-error w-full">Report profile</button>
        </div>

      </div>


    </div>
  </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css"; // Import Swiper styles
import { EffectCreative } from 'swiper/modules';

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
  setup() {
    return {
      modules: [EffectCreative],
    };
  },
};
</script>

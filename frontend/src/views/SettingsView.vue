<script setup>
import ProfileView from '@/components/ProfileView.vue'
import DoubleSlide from '@/components/DoubleSlide.vue'
import { ref, watchEffect} from 'vue'
import TagSelector from "@/components/TagSelector.vue";

const temporaryImage = [
  'https://as1.ftcdn.net/v2/jpg/06/12/64/52/1000_F_612645270_KBcBTf5CToMDyGr1hDpqSNyMK6eaXrPq.jpg',
  'http://as1.ftcdn.net/v2/jpg/09/25/29/78/1000_F_925297895_12R4VyhRAEmvMGHEdGSXXj3B9PTHVYUa.jpg',
  'https://as1.ftcdn.net/v2/jpg/02/44/38/08/1000_F_244380850_H3xd2rrb9CfCIcTyFcepVL670vvuTA0b.jpg',
]

const preferences = ref({
  age: {
    start: 18,
    end: 30,
  },
  distance: 20,
  sexual_preference: 'F',
});

const ageRange = ref();
watchEffect(() => {
  if (ageRange.value) {
    preferences.value.age = {
      start: ageRange.value.t1,
      end: ageRange.value.t2
    };
  }
});

</script>

<template>
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl px-6">
      <div class="flex w-full h-full flex-col gap-3">
        <div class="flex w-full items-center flex-col gap-6">
          <div class="pt-14">
            <ProfileView
              class="w-20 h-36 cursor-pointer"
              :images="temporaryImage"
            />
          </div>
          <div class="text-xl">First Name</div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <button class="btn">Account settings</button>
          </div>
        </div>

        <div class="w-full flex items-center flex-col">
          <div class="card bg-base-300 gap-3 w-full p-5">
            <div class="flex justify-between">
              <label>Age range :</label>
              <label>{{ preferences.age.start }} - {{ preferences.age.end }} years</label>
            </div>
            <DoubleSlide
              v-model="ageRange"
              :min="18"
              :max="80"
              :start="preferences.age.start"
              :end="preferences.age.end"
            />
          </div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <div class="flex justify-between">
              <label>Maximum distance :</label>
              <label>{{ preferences.distance }} Km</label>
            </div>
            <input v-model="preferences.distance" type="range" min="5" max="100" value="preferences.distance" class="range" />
          </div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <div class="flex justify-between">
              <label>Interested by :</label>
            </div>
            <select class="select select-bordered w-full max-w-xs">
              <option :selected="preferences.sexual_preference === 'F'" value="F" >Women</option>
              <option :selected="preferences.sexual_preference === 'M'" value="M" >Man</option>
              <option :selected="preferences.sexual_preference === 'O'" value="O" >Other</option>
              <option :selected="preferences.sexual_preference === 'A'" value="A" >All</option>
            </select>
          </div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <div class="flex justify-between">
              <label>Research by same tags :</label>
              <input type="checkbox" class="toggle" checked="checked" />
            </div>
<!--            <TagSelector class="max-h-72 overflow-y-auto" />-->
          </div>
        </div>

        <div>
          <div class="card bg-base-300 w-full p-5 mt-10">
            <button class="btn btn-outline">Disconect</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

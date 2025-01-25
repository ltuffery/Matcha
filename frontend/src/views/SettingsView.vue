<script setup>
import ProfileView from '@/components/ProfileView.vue'
import DoubleSlide from '@/components/DoubleSlide.vue'
import {onMounted, ref} from "vue";

  const temporaryImage = [
    "https://as1.ftcdn.net/v2/jpg/06/12/64/52/1000_F_612645270_KBcBTf5CToMDyGr1hDpqSNyMK6eaXrPq.jpg",
    "http://as1.ftcdn.net/v2/jpg/09/25/29/78/1000_F_925297895_12R4VyhRAEmvMGHEdGSXXj3B9PTHVYUa.jpg",
    "https://as1.ftcdn.net/v2/jpg/02/44/38/08/1000_F_244380850_H3xd2rrb9CfCIcTyFcepVL670vvuTA0b.jpg"
  ]

const ageSlide = ref()
onMounted(()=>{console.log(ageSlide.value.endValue.value)})
</script>

<template>
	<div class="flex bg-base-300 h-dvh w-full justify-center items-center">
		<div class="bg-base-200 h-full w-full max-w-3xl px-4">
      <div class="flex w-full h-full flex-col">

        <div class="flex self-center items-center flex-col gap-6">
          <div class="pt-14">
            <ProfileView class="w-20 h-36 cursor-pointer" :images="temporaryImage"/>
          </div>
          <div class="text-xl">First Name</div>
        </div>

        <div>Temporary</div>
        <div class="flex">
          <DoubleSlide ref="ageSlide" :min="18" :max="80" :start="18" :end="30" />
        </div>
      </div>
		</div>
	</div>
</template>

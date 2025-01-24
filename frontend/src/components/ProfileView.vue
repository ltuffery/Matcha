<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  images: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <div class="relative select-none image-container">
    <div class="absolute h-full rounded-lg rotation rotation-start" id="rotation-start">
      <img class="h-full w-full object-cover rounded-lg border-2" :src="props.images[2] ? props.images[2] : defaultImage"  alt="profileImg"/>
    </div>
    <div class="absolute h-full rotation rotation-mid" id="rotation-mid">
      <img class="h-full w-full object-cover rounded-lg border-2" :src="props.images[0] ? props.images[0] : defaultImage"  alt="profileImg"/>
    </div>
    <div class="absolute h-full rounded-lg rotation rotation-end" id="rotation-end">
      <img class="h-full w-full object-cover rounded-lg border-2" :src="props.images[1] ? props.images[1] : defaultImage"  alt="profileImgNext"/>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.rotation {
  transition: 0.3s;
  &.rotation-start{
    transform: translateX(45%) translateY(-0.0rem) rotate(12deg);
  }
  &.rotation-mid{
    transform: translateX(0) translateY(-1.5rem);
    z-index: 20;
  }
  &.rotation-end{
    transform: translateX(-45%) translateY(-0.3rem) rotate(-12deg);
  }
}

.image-container:hover{
  transition: 0.5s;
  .rotation-mid {
    transform: translateX(0) translateY(-2.5rem);
  }
  .rotation-start {
    transform: translateX(75%) translateY(-0.0rem) rotate(12deg);
  }
  .rotation-end {
    transform: translateX(-75%) translateY(-0.3rem) rotate(-12deg);
  }
}

</style>

<script setup>
import router from "@/router/index.js";

const props = defineProps({
  username: {
    type: String,
    required: true,
  },
  age: {
    type: String,
    required: true,
  },
  avatar: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div class="card1">
    <div class="card-face">
      <div class="absolute top-0 w-full h-full rounded-box bg-black opacity-20"></div>
      <img :src="props.avatar" class="w-full h-full object-cover rounded-box" />
      <div class="absolute w-4/5 bottom-3 left-3 text-white flex flex-nowrap gap-1"><span class="truncate">{{ props.username }}</span>, <span class="">{{ props.age }}</span></div>
    </div>
    <div class="card-back">
      <button class="btn btn-xs" @click="router.push({ name: 'profile', params: {username: props.username} })">View profile</button>
    </div>
  </div>
</template>

<style scoped>
.card1 {
  @apply relative w-48 h-64 bg-white flex justify-center rounded-box transition-all duration-[0.4s];
  box-shadow: 0 0.625em 0.625em #00000036;
}

.card-face{
  @apply w-full h-full absolute z-10 rounded-box select-none transition-all duration-[0.4s];
  box-shadow: 0 0 0.625em #00000036;
}

.card-back{
  @apply w-full h-[2.188em] absolute bottom-0 left-0 flex justify-center items-center;
}

.card1:hover .card-face {
  transform: translateY(-2.188em);
}
</style>

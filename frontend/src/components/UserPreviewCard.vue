<script setup>
const image_tmp =
  'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'
</script>

<template>
  <div class="card1">
    <div class="card-face">
      <div class="absolute top-0 w-full h-full rounded-box bg-black opacity-20"></div>
      <img :src="image_tmp" class="w-full h-full object-cover rounded-box" />
      <div class="absolute bottom-3 left-3 text-white">Name, 24</div>
    </div>
    <div class="card-back">
      <button class="btn btn-xs">View profile</button>
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

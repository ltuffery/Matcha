<script setup>
const image_tmp =
  'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp'
</script>

<template>
  <div class="flip-card">
    <div class="flip-card-inner">
      <div class="flip-card-front">
        <img
          class="absolute w-full h-full object-cover rounded-box"
          :src="image_tmp"
        />
        <div class="w-full h-full flex flex-col-reverse z-20">
          <div class="bg-base-100/40 pl-2 rounded-b-box">Username, 24</div>
        </div>
      </div>
      <div class="flip-card-back"
        :style="`background-image: url(${image_tmp});`">
        <div class="p-1 w-full h-full flex items-start flex-col gap-1">
          <div>Username, 24</div>
          <div class="w-full">
            <span class="text-sm">Biography :</span>
            <div class="w-full">Content of Bio</div>
          </div>
          <div class="w-full">
            <span class="text-sm">Tags :</span>
            <div class="w-full">Content of tags</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.flip-card {
  //background-color: transparent;
  @apply h-64 w-44 cursor-pointer;
  //width: 190px;
  //height: 254px;
  perspective: 1000px;
  //font-family: sans-serif;
}

.flip-card-inner {
  @apply relative w-full h-full;
  //position: relative;
  //width: 100%;
  //height: 100%;
  //text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}

.flip-card:hover .flip-card-inner {
  transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
  //box-shadow: 0 8px 14px 0 rgba(0, 0, 0, 0.2);
  @apply absolute flex w-full h-full rounded-box border border-neutral;
  //position: absolute;
  //display: flex;
  //flex-direction: column;
  //justify-content: center;
  //width: 100%;
  //height: 100%;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  //border: 1px solid coral;
  //border-radius: 1rem;
}

.flip-card-front {
  //background: linear-gradient(120deg, bisque 60%, rgb(255, 231, 222) 88%,
  //rgb(255, 211, 195) 40%, rgba(255, 127, 80, 0.603) 48%);
  //color: coral;
}

.flip-card-back {
  //background: linear-gradient(120deg, rgb(255, 174, 145) 30%, coral 88%,
  //bisque 40%, rgb(255, 185, 160) 78%);
  //color: white;
  @apply backdrop-blur-sm;
  transform: rotateY(180deg);
}
</style>

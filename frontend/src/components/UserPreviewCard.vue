<script setup>

</script>

<template>
  <div class="flip-card">
    <div class="flip-card-inner">
      <div class="flip-card-front">
        <img class="absolute w-full h-full object-cover rounded-box" src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
<!--        <p class="title">FLIP CARD</p>-->
        <p class="z-10">Hover Me</p>
      </div>
      <div class="flip-card-back">
        ho ui
<!--        <p class="title">BACK</p>-->
<!--        <p>Leave Me</p>-->
      </div>
    </div>
  </div>
</template>

<style scoped>
.flip-card {
  //background-color: transparent;
  width: 190px;
  height: 254px;
  perspective: 1000px;
  //font-family: sans-serif;
}

.flip-card-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}

.flip-card:hover .flip-card-inner {
  transform: rotateY(180deg);
}

.flip-card-front, .flip-card-back {
  //box-shadow: 0 8px 14px 0 rgba(0,0,0,0.2);
  @apply absolute w-full h-full rounded-box flex flex-col justify-center;
  //position: absolute;
  //display: flex;
  //flex-direction: column;
  //justify-content: center;
  //width: 100%;
  //height: 100%;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  border: 1px solid coral;
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
  transform: rotateY(180deg);
}
</style>

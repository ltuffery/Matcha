<template>
  <div class="range-container">
    <input ref="startValue" type="range" @input="handleInput" id="startValue" :min="props.min" :max="props.max" :value="props.start">
    <input ref="endValue" type="range" @input="handleInput" id="endValue" :min="props.min" :max="props.max" :value="props.end">
    <div ref="tooltipStart" class="tooltip" id="tooltipStart"></div>
    <div ref="tooltipEnd" class="tooltip" id="tooltipEnd"></div>
    <div ref="sliderTrack" class="slider-track"></div>
    <div class="baseTrack range"></div>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
  min: {
    type: Number,
    default: 0
  },
  max: {
    type: Number,
    default: 100
  },
  start: {
    type: Number,
    default: 0
  },
  end: {
    type: Number,
    default: 0
  }
})

const startValue = ref()
const endValue = ref()
const tooltipStart = ref()
const tooltipEnd = ref()
const sliderTrack = ref()

const handleInput = (e) => {
  if (startValue.value.value > endValue.value.value) {
    if (e.target.id === 'startValue')
      startValue.value.value = endValue.value.value;
    else
      endValue.value.value = startValue.value.value;
  }
  updateValues();
}

function updateValues() {
  tooltipStart.value.textContent = startValue.value.value;
  tooltipEnd.value.textContent = endValue.value.value;

  // Position tooltips above range sliders
  const startPercent = ((startValue.value.value - props.min) / (props.max - props.min)) * 100;
  const endPercent = ((endValue.value.value - props.min) / (props.max - props.min)) * 100;

  tooltipStart.value.style.left = `calc(${startPercent}% - 0.94em)`;
  tooltipEnd.value.style.left = `calc(${endPercent}% - 0.94em)`;

  // Update track background color range
  sliderTrack.value.style.left = `${startPercent}%`;
  sliderTrack.value.style.width = `${endPercent - startPercent}%`;
}

onMounted(() => {
  updateValues();
})

defineExpose({
  startValue,
  endValue
})

</script>


<style lang="scss" scoped>
.range-container {
  position: relative;
  width: 100%;
  height: 1.5em;
}

input[type="range"] {
  position: absolute;
  width: 100%;
  pointer-events: none;  /* Disable direct interaction */
  appearance: none;
  height: 0.5em;
  background: transparent;
  outline: none;
}

input[type="range"]::-webkit-slider-thumb {
  pointer-events: auto;
  appearance: none;
  width: 1.5em;
  height: 1.5em;
  background: var(--fallback-b1,oklch(var(--b1)/1));
  border: 0.3rem solid var(--fallback-bc,oklch(var(--bc)/1));
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  margin-top: 1em; // mouais
  z-index: 3;
}

.slider-track {
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  height: 0.9em;
  background: var(--fallback-bc,oklch(var(--bc)/1));
  border-radius: 0.31em;
  transform: translateY(-50%);
  z-index: 2;
}

.baseTrack{
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  height: 0.5em;
  background: var(--fallback-bc,oklch(var(--bc)/0.1));
  border-radius: 0.31em;
  transform: translateY(-50%);
  z-index: 1;
}

.tooltip {
  position: absolute;
  background: var(--fallback-bc,oklch(var(--bc)/0.8));
  color: var(--fallback-b1,oklch(var(--b1)/1));
  padding: 0.31em 0.63em;
  border-radius: 0.31em;
  font-size: 0.75em;
  transform: translateY(-2.19em);
  white-space: nowrap;
  pointer-events: none;
  opacity: 1;
  transition: opacity 0.3s ease;
}

</style>

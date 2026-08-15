<script setup lang="ts">
import { onMounted, ref, defineEmits } from 'vue'

const props = defineProps<{
  min?: number
  max?: number
  start?: number
  end?: number
  tooltip?: boolean
}>()

const startValue = ref<HTMLInputElement>()
const endValue = ref<HTMLInputElement>()
const tooltipStart = ref<HTMLDivElement>()
const tooltipEnd = ref<HTMLDivElement>()
const sliderTrack = ref<HTMLDivElement>()
const baseTrack = ref<HTMLDivElement>()

const emit = defineEmits<{
  'update:modelValue': [value: { t1: number; t2: number }]
}>()

function getThumbPosition(range: HTMLInputElement): number {
  const rect = range.getBoundingClientRect()
  const min = parseFloat(range.min)
  const max = parseFloat(range.max)
  const value = parseFloat(range.value)

  const percent = (value - min) / (max - min)
  return rect.left + percent * rect.width
}

const getValueOfClick = (div: HTMLDivElement, posClickX: number): number => {
  const rect = div.getBoundingClientRect()
  const startPos = rect.left
  const endPos = rect.left + rect.width
  return (
    (props.min ?? 0) +
    ((posClickX - startPos) / (endPos - startPos)) * ((props.max ?? 100) - (props.min ?? 0))
  )
}

function updateValues() {
  if (!startValue.value || !endValue.value || !tooltipStart.value || !tooltipEnd.value || !sliderTrack.value)
    return

  tooltipStart.value.textContent = startValue.value.value
  tooltipEnd.value.textContent = endValue.value.value

  const startPercent =
    ((parseFloat(startValue.value.value) - (props.min ?? 0)) / ((props.max ?? 100) - (props.min ?? 0))) * 100
  const endPercent =
    ((parseFloat(endValue.value.value) - (props.min ?? 0)) / ((props.max ?? 100) - (props.min ?? 0))) * 100

  tooltipStart.value.style.left = `calc(${startPercent}% - 0.94em)`
  tooltipEnd.value.style.left = `calc(${endPercent}% - 0.94em)`

  sliderTrack.value.style.left = `${startPercent}%`
  sliderTrack.value.style.width = `${endPercent - startPercent}%`
}

const handleClick = (e: MouseEvent) => {
  if (!startValue.value || !endValue.value || !baseTrack.value) return

  const thumb1X = getThumbPosition(startValue.value)
  const thumb2X = getThumbPosition(endValue.value)
  const distance1 = Math.abs(thumb1X - e.x)
  const distance2 = Math.abs(thumb2X - e.x)

  const value = getValueOfClick(baseTrack.value, e.x)

  if (distance1 === distance2) {
    if (value < parseFloat(startValue.value.value)) startValue.value.value = String(value)
    else endValue.value.value = String(value)
  } else if (distance1 < distance2) startValue.value.value = String(value)
  else endValue.value.value = String(value)
  updateValues()
  const temporary = {
    t1: parseInt(startValue.value.value),
    t2: parseInt(endValue.value.value),
  }
  emit('update:modelValue', temporary)
}

const handleInput = (e: Event) => {
  if (!startValue.value || !endValue.value) return

  if (parseFloat(startValue.value.value) > parseFloat(endValue.value.value)) {
    const target = e.target as HTMLInputElement
    if (target.id === 'startValue')
      startValue.value.value = endValue.value.value
    else endValue.value.value = startValue.value.value
  }
  updateValues()
  const temporary = {
    t1: parseInt(startValue.value.value),
    t2: parseInt(endValue.value.value),
  }
  emit('update:modelValue', temporary)
}

onMounted(() => {
  if (!startValue.value || !endValue.value) return
  startValue.value.value = String(props.start ?? 0)
  endValue.value.value = String(props.end ?? 0)
  updateValues()
})
</script>

<template>
  <div class="range-container">
    <input
      ref="startValue"
      type="range"
      @input="handleInput"
      id="startValue"
      :min="props.min ?? 0"
      :max="props.max ?? 100"
      :value="props.start ?? 0"
      class="z-20 mt-[0.5em]"
    />
    <input
      ref="endValue"
      type="range"
      @input="handleInput"
      id="endValue"
      :min="props.min ?? 0"
      :max="props.max ?? 100"
      :value="props.end ?? 0"
      class="z-20 mt-[0.5em]"
    />
    <div
      ref="tooltipStart"
      :style="props.tooltip ? '' : 'opacity: 0;'"
      class="tooltip"
      id="tooltipStart"
    ></div>
    <div
      ref="tooltipEnd"
      :style="props.tooltip ? '' : 'opacity: 0;'"
      class="tooltip"
      id="tooltipEnd"
    ></div>
    <div ref="sliderTrack" @click="handleClick" class="slider-track"></div>
    <div ref="baseTrack" @click="handleClick" class="baseTrack"></div>
  </div>
</template>

<style lang="scss" scoped>
.range-container {
  position: relative;
  width: 100%;
  height: 1.5em;

  input[type='range'] {
    position: absolute;
    width: 100%;
    pointer-events: none;
    appearance: none;
    height: 0.5em;
    background: transparent;
    outline: none;
  }

  input[type='range']::-webkit-slider-thumb {
    pointer-events: auto;
    appearance: none;
    width: 1.5em;
    height: 1.5em;
    background: hsl(var(--background));
    border: 0.3rem solid hsl(var(--foreground));
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    z-index: 3;
  }

  input[type='range']::-moz-range-thumb {
    pointer-events: auto;
    width: 1.5em;
    height: 1.5em;
    background: hsl(var(--background));
    border: 0.3rem solid hsl(var(--foreground));
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    z-index: 3;
  }

  .slider-track {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 0.9em;
    background: hsl(var(--foreground));
    border-radius: 0.31em;
    transform: translateY(-50%);
    z-index: 2;
  }

  .baseTrack {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 0.5em;
    background: hsl(var(--foreground) / 0.1);
    border-radius: 0.31em;
    transform: translateY(-50%);
    z-index: 1;
  }

  .tooltip {
    position: absolute;
    background: hsl(var(--foreground) / 0.8);
    color: hsl(var(--background));
    padding: 0.31em 0.63em;
    border-radius: 0.31em;
    font-size: 0.75em;
    transform: translateY(-2.19em);
    white-space: nowrap;
    pointer-events: none;
    opacity: 1;
    transition: opacity 0.3s ease;
  }
}
</style>

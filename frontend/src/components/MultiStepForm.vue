<script setup lang="ts">
import { defineProps, ref } from 'vue'

const props = defineProps<{
  totalSteps: number
}>()

const currentStep = ref(0)

function nextStep() {
  if (currentStep.value < props.totalSteps) {
    currentStep.value++
  }
}

function prevStep() {
  if (currentStep.value > 0) {
    currentStep.value--
  }
}

function setStep(step: number) {
  if (step >= 0 && step < props.totalSteps) {
    currentStep.value = step
  }
}

defineExpose({
  currentStep,
  nextStep,
  setStep,
})
</script>

<template>
  <div class="multi-step-form">
    <div v-if="currentStep < props.totalSteps">
      <div :key="currentStep">
        <slot :name="'step-' + currentStep" />
      </div>

      <div class="flex gap-4">
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep > 0"
          @click="prevStep"
        >
          <button class="btn" type="button">Previous</button>
        </div>
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep < props.totalSteps - 1"
          @click="nextStep"
        >
          <button class="btn btn-primary" type="button">Next</button>
        </div>
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep === props.totalSteps - 1"
        >
          <button class="btn btn-primary" type="submit">Submit</button>
        </div>
      </div>
    </div>
    <div v-else>
      <slot name="finish"></slot>
    </div>
  </div>
</template>

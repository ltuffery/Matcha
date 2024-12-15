<script setup>
import { defineProps, defineEmits, ref } from 'vue'

const props = defineProps({
  totalSteps: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['submit', 'changeStep'])

let currentStep = ref(0)
emit('changeStep', currentStep)

function nextStep() {
  if (currentStep.value < props.totalSteps - 1) {
    currentStep.value++
    emit('changeStep', currentStep)
  }
}

function prevStep() {
  if (currentStep.value > 0) {
    currentStep.value--
    emit('changeStep', currentStep)
  }
}

function submitForm() {
  // currentStep.value++
  emit('changeStep', currentStep)
  emit('submit')
}
</script>

<template>
  <div class="multi-step-form">
    <div v-if="currentStep < props.totalSteps">
      <!-- Affichage de l'étape courante -->
      <div :key="currentStep">
        <slot :name="'step-' + currentStep" />
      </div>

      <!-- Navigation entre les étapes -->
      <div class="flex gap-4">
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep > 0"
          @click="prevStep"
        >
          <button class="btn btn-primary" type="submit">Précédent</button>
        </div>
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep < props.totalSteps - 1"
          @click="nextStep"
        >
          <button class="btn btn-primary" type="submit">Suivant</button>
        </div>
        <div
          class="form-control mt-6 w-full"
          v-if="currentStep === props.totalSteps - 1"
          @click="submitForm"
        >
          <button class="btn btn-primary" type="submit">Soumettre</button>
        </div>
      </div>
    </div>
    <div v-else>
      <slot name="finish"></slot>
    </div>
  </div>
</template>

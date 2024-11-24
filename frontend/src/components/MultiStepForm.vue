<script setup>
import { defineProps, defineEmits, ref } from 'vue';

const props = defineProps({
  totalSteps: {
    type: Number,
    required: true
  }
})

let currentStep = ref(0)

function nextStep() {
  if (currentStep.value < props.totalSteps - 1) {
    currentStep.value++;
  }
}

function prevStep() {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
}

const emit = defineEmits(['submit'])

function submitForm() {
  currentStep.value++
  emit('submit');
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
        <div class="form-control mt-6 w-full" v-if="currentStep > 0" @click="prevStep">
          <button class="btn btn-primary" type="submit">Précédent</button>
        </div>
        <div class="form-control mt-6 w-full" v-if="currentStep < props.totalSteps - 1" @click="nextStep">
          <button class="btn btn-primary" type="submit">Suivant</button>
        </div>
        <div class="form-control mt-6 w-full" v-if="currentStep === props.totalSteps - 1" @click="submitForm">
          <button class="btn btn-primary" type="submit">Soumettre</button>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Merci pour votre soumission !</p>
    </div>
  </div>
</template>

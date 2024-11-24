<script setup>
import MultiStepForm from '@/components/MultiStepForm.vue';
import { ref } from 'vue';

let formData = {
  username: '',
  email: '',
  address: '',
  phone: '',
  acceptTerms: false
}

const totalSteps = 3
let step = ref(0);

function handleSubmit(message) {
  console.log(message);
  console.log(formData);
  alert('Formulaire soumis avec succès !');
}

function handleChangeStep(n) {
  step.value = n
}
</script>

<template>
  <progress class="progress progress-primary fixed top-0 rounded-none" :value="step.value + 1" :max="totalSteps"></progress>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <MultiStepForm :totalSteps="totalSteps" @submit="handleSubmit" @change-step="handleChangeStep">
          <template #step-0>
            <h3>Étape 1 : Informations personnelles</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">First Name</span>
                </label>
                <input v-model="formData.username" type="text" placeholder="First Name" class="input input-bordered" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Last Name</span>
                </label>
                <input v-model="formData.username" type="text" placeholder="last Name" class="input input-bordered" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Age</span>
                </label>
                <input v-model="formData.username" type="number" placeholder="Age" class="input input-bordered" min="18" required />
              </div>
            </form>
          </template>

          <template #step-1>
            <h3>Étape 2 : Détails supplémentaires</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Gender</span>
                </label>
                <select class="select select-bordered w-full max-w-xs">
                  <option>M</option>
                  <option>F</option>
                  <option>O</option>
                </select>
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Sexual Preferences</span>
                </label>
                <select class="select select-bordered w-full max-w-xs">
                  <option>M</option>
                  <option>F</option>
                  <option>O</option>
                  <option>A</option>
                </select>
              </div>
            </form>
          </template>

          <template #step-2>
            <h3>Étape 3 : Confirmation</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Bio</span>
                </label>
                <textarea class="textarea textarea-bordered" placeholder="Bio"></textarea>
              </div>
            </form>
          </template>
        </MultiStepForm>
      </div>
    </div>
  </main>
</template>

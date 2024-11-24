<script setup>
import MultiStepForm from '@/components/MultiStepForm.vue';

let formData = {
  name: '',
  email: '',
  address: '',
  phone: '',
  acceptTerms: false
}

function handleSubmit(message) {
  console.log(message);
  console.log(formData);
  alert('Formulaire soumis avec succès !');
}
</script>

<template>
  <progress class="progress progress-primary fixed top-0 rounded-none" value="1" max="3"></progress>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <MultiStepForm :totalSteps="3" @submit="handleSubmit">
          <template #step-0>
            <h3>Étape 1 : Informations personnelles</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Username</span>
                </label>
                <input v-model="username" type="text" placeholder="username" class="input input-bordered" required />
              </div>
            </form>
          </template>

          <template #step-1>
            <h3>Étape 2 : Détails supplémentaires</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Username</span>
                </label>
                <input v-model="username" type="text" placeholder="username" class="input input-bordered" required />
              </div>
            </form>
          </template>

          <template #step-2>
            <h3>Étape 3 : Confirmation</h3>
            <form>
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Username</span>
                </label>
                <input v-model="username" type="text" placeholder="username" class="input input-bordered" required />
              </div>
            </form>
          </template>
        </MultiStepForm>
      </div>
    </div>
  </main>
</template>
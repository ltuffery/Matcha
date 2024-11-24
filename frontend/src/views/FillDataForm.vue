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
  <MultiStepForm :totalSteps="3" @submit="handleSubmit">
      <template #step-0>
        <h3>Étape 1 : Informations personnelles</h3>
        <form>
          <label for="name">Nom:</label>
          <input v-model="formData.name" type="text" id="name" placeholder="Entrez votre nom" />
          
          <label for="email">Email:</label>
          <input v-model="formData.email" type="email" id="email" placeholder="Entrez votre email" />
        </form>
      </template>

      <template #step-1>
        <h3>Étape 2 : Détails supplémentaires</h3>
        <form>
          <label for="address">Adresse:</label>
          <input v-model="formData.address" type="text" id="address" placeholder="Entrez votre adresse" />

          <label for="phone">Téléphone:</label>
          <input v-model="formData.phone" type="text" id="phone" placeholder="Entrez votre numéro" />
        </form>
      </template>

      <template #step-2>
        <h3>Étape 3 : Confirmation</h3>
        <form>
          <label for="acceptTerms">
            <input v-model="formData.acceptTerms" type="checkbox" id="acceptTerms" />
            Acceptez-vous les termes et conditions ?
          </label>
        </form>
      </template>
    </MultiStepForm>
</template>
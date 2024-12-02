<script lang="ts" setup>
import MultiStepForm from '@/components/MultiStepForm.vue'
import { Api } from '@/utils/api'
import { ref } from 'vue'
import Alert from './Alert.vue';

let formData = {
  username: '',
  email: '',
  password: '',
  age: 0,
  first_name: '',
  last_name: '',
  gender: '' as 'M' | 'F' | 'O',
  sexual_preferences: '' as 'M' | 'F' | 'A' | 'O',
  biography: '',
}

let formError = {
  username: ref(false),
  email: ref(false),
  password: ref(false),
  age: ref(false),
  first_name: ref(false),
  last_name: ref(false),
  gender: ref(false),
  sexual_preferences: ref(false),
  biography: ref(false),
}

const totalSteps = 4
const step = ref(0)
const hasError = ref(false)
const titleAlert = ref('test')

async function handleSubmit() {
  try {
    const req = await Api.post('/auth/register').send(formData);
    const data = await req.json();

    if (req.status == 400) {
      hasError.value = true;
      titleAlert.value = data.message;
    }
  } catch (error) {
    console.error("Error during API request:", error);
    hasError.value = true;
    titleAlert.value = "An unexpected error occurred.";
  }
}

function handleChangeStep(n: number) {
  step.value = n
}

function handleInput(e) {
  switch (e.target.type) {
    case "text":
      formError[e.target.name].value = !/^[a-zA-Z_-]+$/.test(e.target.value)
      break
    case "email":
      formError[e.target.name].value = !/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(e.target.value)
      break
  }
}
</script>

<template>
  <progress
    class="progress progress-primary fixed top-0 left-0 rounded-none"
    :value="step.value"
    :max="totalSteps"
  ></progress>
  <Alert v-if="hasError" type="error" :title="titleAlert" />
  <MultiStepForm
    :totalSteps="totalSteps"
    @submit="handleSubmit"
    @change-step="handleChangeStep"
  >
    <template #step-0>
      <div class="form-control">
        <label class="label">
          <span class="label-text">Username</span>
        </label>
        <input
          name="username"
          v-model="formData.username"
          type="text"
          placeholder="username"
          class="input input-bordered"
          :class="{'input-error': formError.username.value}"
          @input="handleInput"
          required
        />
      </div>
      <div class="form-control">
        <label class="label">
          <span class="label-text">Email</span>
        </label>
        <input
          name="email"
          v-model="formData.email"
          type="email"
          placeholder="email"
          class="input input-bordered"
          :class="{'input-error': formError.email.value}"
          @input="handleInput"
          required
        />
      </div>
      <div class="form-control">
        <label class="label">
          <span class="label-text">Password</span>
        </label>
        <input
          name="password"
          v-model="formData.password"
          type="password"
          placeholder="password"
          class="input input-bordered"
          @input="handleInput"
          required
        />
      </div>
    </template>

    <template #step-1>
      <form>
        <div class="form-control">
          <label class="label">
            <span class="label-text">First Name</span>
          </label>
          <input
            name="first_name"
            v-model="formData.first_name"
            type="text"
            placeholder="First Name"
            class="input input-bordered"
            @input="handleInput"
            required
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Last Name</span>
          </label>
          <input
            name="last_name"
            v-model="formData.last_name"
            type="text"
            placeholder="last Name"
            class="input input-bordered"
            @input="handleInput"
            required
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Age</span>
          </label>
          <input
            name="age"
            v-model="formData.age"
            type="number"
            placeholder="Age"
            class="input input-bordered"
            @input="handleInput"
            min="18"
            required
          />
        </div>
      </form>
    </template>

    <template #step-2>
      <form>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Gender</span>
          </label>
          <select
            name="gender"
            v-model="formData.gender"
            class="select select-bordered w-full max-w-xs"
            @input="handleInput"
          >
            <option>M</option>
            <option>F</option>
            <option>O</option>
          </select>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Sexual Preferences</span>
          </label>
          <select
            name="sexual_preferences"
            v-model="formData.sexual_preferences"
            class="select select-bordered w-full max-w-xs"
            @input="handleInput"
          >
            <option>M</option>
            <option>F</option>
            <option>O</option>
            <option>A</option>
          </select>
        </div>
      </form>
    </template>

    <template #step-3>
      <form>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Bio</span>
          </label>
          <textarea
            name="biography"
            v-model="formData.biography"
            class="textarea textarea-bordered"
            @input="handleInput"
            placeholder="Bio"
          ></textarea>
        </div>
      </form>
    </template>

    <template #finish>
      <p>Compte créé avec succès, connectez-vous.</p>
    </template>
  </MultiStepForm>
</template>

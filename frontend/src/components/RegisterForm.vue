<script lang="ts" setup>
import MultiStepForm from '@/components/MultiStepForm.vue'
import { Api } from '@/utils/api'
import { ref } from 'vue'
import Alert from './Alert.vue'

let formData = {
  username: '',
  email: '',
  password: '',
  age: 0,
  birthday: '',
  first_name: '',
  last_name: '',
  gender: '' as 'M' | 'F' | 'O',
  sexual_preferences: '' as 'M' | 'F' | 'A' | 'O',
  biography: '',
}

const totalSteps = 4
const step = ref(0)
const hasError = ref(false)
const titleAlert = ref('test')
const maxAge = ref()

function setMaxAge()
{
  const maxDate = new Date();
  maxDate.setFullYear(new Date().getFullYear() - 18);

  const formattedDate = maxDate.toISOString().split('T')[0];
  maxAge.value = formattedDate
}
setMaxAge()

async function handleSubmit() {
  try {
    const req = await Api.post('/auth/register').send(formData)
    const data = await req.json()

    if (req.status == 400) {
      hasError.value = true
      titleAlert.value = data.message
    } else {
      Api.post('email/verif').send({ email: formData.email })
    }
  } catch (error) {
    console.error('Error during API request:', error)
    hasError.value = true
    titleAlert.value = 'An unexpected error occurred.'
  }
}

function handleChangeStep(n: number) {
  step.value = n
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
          v-model="formData.username"
          type="text"
          placeholder="username"
          class="input input-bordered"
          required
        />
      </div>
      <div class="form-control">
        <label class="label">
          <span class="label-text">Email</span>
        </label>
        <input
          v-model="formData.email"
          type="email"
          placeholder="email"
          class="input input-bordered"
          required
        />
      </div>
      <div class="form-control">
        <label class="label">
          <span class="label-text">Password</span>
        </label>
        <input
          v-model="formData.password"
          type="password"
          placeholder="password"
          class="input input-bordered"
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
            v-model="formData.first_name"
            type="text"
            placeholder="First Name"
            class="input input-bordered"
            required
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Last Name</span>
          </label>
          <input
            v-model="formData.last_name"
            type="text"
            placeholder="last Name"
            class="input input-bordered"
            required
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Age</span>
          </label>
          <input
            v-model="formData.age"
            type="number"
            placeholder="Age"
            class="input input-bordered"
            min="18"
            required
          />

          <input v-model="formData.birthday" class="input input-bordered" type="date" :max="maxAge"/>
          
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
            v-model="formData.gender"
            class="select select-bordered w-full max-w-xs"
          >
            <option value="M">Man</option>
            <option value="F">Woman</option>
            <option value="O">Other</option>
          </select>
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Sexual Preferences</span>
          </label>
          <select
            v-model="formData.sexual_preferences"
            class="select select-bordered w-full max-w-xs"
          >
            <option value="M">Men</option>
            <option value="F">women</option>
            <option value="O">Other</option>
            <option value="A">All</option>
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
            v-model="formData.biography"
            class="textarea textarea-bordered"
            placeholder="Bio"
          ></textarea>
        </div>
      </form>
    </template>

    <template #finish>
      <p>Account created successfully, check your email before you connect.</p>
    </template>
  </MultiStepForm>
</template>

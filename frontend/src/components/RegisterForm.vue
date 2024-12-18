<script lang="ts" setup>
import MultiStepForm from '@/components/MultiStepForm.vue'
import { Api } from '@/utils/api'
import { ref, watch } from 'vue'
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

const formError = ref({
  username: false,
  email: false,
  password: false,
  age: false,
  birthday: false,
  first_name: false,
  last_name: false,
  gender: false,
  sexual_preferences: false,
  biography: false,
})

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

function inputIsValidStep0()
{
  if (formData.username == '' || formData.email == '' || formData.password == '')
  {
    if (formData.username == '')
      formError.value.username = true
    if (formData.email == '')
      formError.value.email = true
    if (formData.password == '')
      formError.value.password = true
    return false
  }
  return true
}

function inputIsValidStep1()
{
  if (formData.first_name == '' || formData.last_name == '' || formData.birthday == '' || formData.birthday > maxAge.value)
  {
    if (formData.first_name == '')
      formError.value.first_name = true
    if (formData.last_name == '')
      formError.value.last_name = true
    if (formData.birthday == '' || formData.birthday > maxAge.value)
      formError.value.birthday = true
    return false
  }
  return true
}

function inputIsValidStep2()
{
  if (formData.gender == '' || formData.sexual_preferences == '')
  {
    if (formData.gender == '')
      formError.value.gender = true
    if (formData.sexual_preferences == '')
      formError.value.sexual_preferences = true
    return false
  }
  return true
}

function validatorByStep(currentStep)
{
  switch (currentStep) {
    case 0:
      return inputIsValidStep0()
    case 1:
      return inputIsValidStep1()
    case 2:
      return inputIsValidStep2()
    default:
      break;
  }
  return (true)
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
    :validatorByStep="validatorByStep"
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
          :class="{ 'input-error': formError.username }"
          @focus="formError.username = false"
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
          :class="{ 'input-error': formError.email }"
          @focus="formError.email = false"
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
          :class="{ 'input-error': formError.password }"
          @focus="formError.password = false"
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
            :class="{ 'input-error': formError.first_name }"
            @focus="formError.first_name = false"
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
            :class="{ 'input-error': formError.last_name }"
            @focus="formError.last_name = false"
            required
          />
        </div>

        <div class="form-control">
          <label class="label">
            <span class="label-text">Age</span>
          </label>
          <input
            v-model="formData.birthday"
            class="input input-bordered"
            type="date" :max="maxAge"
            :class="{ 'input-error': formError.birthday }"
            @focus="formError.birthday = false"
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
            v-model="formData.gender"
            class="select select-bordered w-full max-w-xs"
            :class="{ 'select-error': formError.gender }"
            @focus="formError.gender = false"
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
            :class="{ 'select-error': formError.sexual_preferences }"
            @focus="formError.sexual_preferences = false"
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

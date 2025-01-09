<script lang="ts" setup>
import MultiStepForm from '@/components/MultiStepForm.vue'
import { Api } from '@/utils/api'
import { ref, computed, onMounted, nextTick } from 'vue'
import FeedbackToast from '@/components/FeedbackToast.vue'

const formData = {
  username: null,
  email: null,
  password: null,
  birthday: null,
  first_name: null,
  last_name: null,
  gender: null as 'M' | 'F' | 'O',
  sexual_preferences: null as 'M' | 'F' | 'A' | 'O',
  biography: null,
}

const refs = ref({})
const step = ref()
const currentStep = computed(() => {
  return step?.value?.currentStep || 0
})

const totalSteps = 4
const feedbackRef = ref()
const maxAge = ref()

function setMaxAge() {
  const maxDate = new Date()
  maxDate.setFullYear(new Date().getFullYear() - 18)

  const formattedDate = maxDate.toISOString().split('T')[0]
  maxAge.value = formattedDate
}
setMaxAge()

async function handleSubmit() {
  console.log(formData)
  try {
    const req = await Api.post('/auth/register').send(formData)
    const data = await req.json()

    if (req.status == 400) {
      feedbackRef.value.addError(data.message)
      focusInput(getErrorCode(data.message))
    } else {
      Api.post('email/verif').send({ email: formData.email })
      step.value.nextStep()
    }
  } catch (error) {
    console.error('Error during API request:', error)
    feedbackRef.value.addError('An unexpected error occurred.')
  }
}

function getErrorCode(message) {
  if (message.search('username') != -1) return 0
  else if (message.search('email') != -1) return 1
  else if (message.search('password') != -1) return 2
  else if (message.search('first_name') != -1) return 3
  else if (message.search('last_name') != -1) return 4
  else if (message.search('birthday') != -1) return 5
  else if (message.search('gender') != -1) return 6
  else if (message.search('sexual_preferences') != -1) return 7
  else if (message.search('biography') != -1) return 8
}

function focusInput(code) {
  if (code < 3) step.value.setStep(0)
  else if (code >= 3 && code < 6) step.value.setStep(1)
  else if (code >= 6 && code < 8) step.value.setStep(2)
  else step.value.setStep(3)

  nextTick(() => {
    switch (code) {
      case 0:
        refs.value.username.focus()
        refs.value.username.classList.add('input-error')
        break
      case 1:
        refs.value.email.focus()
        refs.value.email.classList.add('input-error')
        break
      case 2:
        refs.value.password.focus()
        refs.value.password.classList.add('input-error')
        break
      case 3:
        refs.value.first_name.focus()
        refs.value.first_name.classList.add('input-error')
        break
      case 4:
        refs.value.last_name.focus()
        refs.value.last_name.classList.add('input-error')
        break
      case 5:
        refs.value.birthday.focus()
        refs.value.birthday.classList.add('input-error')
        break
      case 6:
        refs.value.gender.focus()
        refs.value.gender.classList.add('select-error')
        break
      case 7:
        refs.value.sexual_preferences.focus()
        refs.value.sexual_preferences.classList.add('select-error')
        break
      case 8:
        refs.value.biography.focus()
        refs.value.biography.classList.add('textarea-error')
        break

      default:
        break
    }
  })
}

function eraseErrorStyle(el) {
  el.target.classList.remove('input-error')
  el.target.classList.remove('select-error')
  el.target.classList.remove('textarea-error')
}
</script>

<template>
  <progress
    class="progress progress-primary fixed top-0 left-0 rounded-none"
    :value="currentStep"
    :max="totalSteps"
  ></progress>
  <FeedbackToast ref="feedbackRef" class="w-full" />

  <form @submit.prevent="handleSubmit">
    <MultiStepForm :totalSteps="totalSteps" @submit="handleSubmit" ref="step">
      <template #step-0>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Username</span>
          </label>
          <input
            v-model="formData.username"
            :ref="el => (refs.username = el)"
            type="text"
            placeholder="username"
            class="input input-bordered"
            @input="eraseErrorStyle"
            required
          />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Email</span>
          </label>
          <input
            v-model="formData.email"
            :ref="el => (refs.email = el)"
            type="email"
            placeholder="email"
            class="input input-bordered"
            @input="eraseErrorStyle"
            required
          />
        </div>
        <div class="form-control">
          <label class="label">
            <span class="label-text">Password</span>
          </label>
          <input
            v-model="formData.password"
            :ref="el => (refs.password = el)"
            type="password"
            placeholder="password"
            class="input input-bordered"
            @input="eraseErrorStyle"
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
              :ref="el => (refs.first_name = el)"
              type="text"
              placeholder="First Name"
              class="input input-bordered"
              @input="eraseErrorStyle"
              required
            />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text">Last Name</span>
            </label>
            <input
              v-model="formData.last_name"
              :ref="el => (refs.last_name = el)"
              type="text"
              placeholder="last Name"
              class="input input-bordered"
              @input="eraseErrorStyle"
              required
            />
          </div>

          <div class="form-control">
            <label class="label">
              <span class="label-text">Birthday</span>
            </label>
            <input
              v-model="formData.birthday"
              :ref="el => (refs.birthday = el)"
              class="input w-full input-bordered"
              type="date"
              :max="maxAge"
              @input="eraseErrorStyle"
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
              :ref="el => (refs.gender = el)"
              class="select select-bordered w-full max-w-xs"
              @input="eraseErrorStyle"
            >
              <option disabled selected>Choose one</option>
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
              :ref="el => (refs.sexual_preferences = el)"
              class="select select-bordered w-full max-w-xs"
              @input="eraseErrorStyle"
            >
              <option disabled selected>Choose one</option>
              <option value="M">Men</option>
              <option value="F">Women</option>
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
              :ref="el => (refs.biography = el)"
              class="textarea textarea-bordered"
              @input="eraseErrorStyle"
              placeholder="Bio"
            ></textarea>
          </div>
        </form>
      </template>

      <template #finish>
        <p>
          Account created successfully, check your email before you connect.
        </p>
      </template>
    </MultiStepForm>
  </form>
</template>

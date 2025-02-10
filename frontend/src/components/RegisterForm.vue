<script setup>
import MultiStepForm from '@/components/MultiStepForm.vue'
import { Api } from '@/utils/api'
import { ref, computed, onMounted, nextTick } from 'vue'
import FeedbackToast from '@/components/FeedbackToast.vue'
import TagSelector from '@/components/TagSelector.vue'

const formData = {
  username: null,
  email: null,
  password: null,
  birthday: null,
  first_name: null,
  last_name: null,
  gender: null,
  biography: null,
  tags: [],
  images: [],
}

const refs = ref({})
const step = ref()
const images = ref([])
const currentStep = computed(() => {
  return step?.value?.currentStep || 0
})

const totalSteps = 5
const feedbackRef = ref()
const maxAge = ref()

const handleFileUpload = e => {
  for (let index = 0; index < e.target.files.length; index++) {
    if (images.value.length == 5) {
      break
    }

    const element = {
      file: e.target.files[index],
      url: URL.createObjectURL(e.target.files[index]),
    }

    images.value.push(element)
  }
}

const handleRemoveFileUpload = index => {
  images.value.splice(index, 1)
}

function setMaxAge() {
  const maxDate = new Date()
  maxDate.setFullYear(new Date().getFullYear() - 18)

  const formattedDate = maxDate.toISOString().split('T')[0]
  maxAge.value = formattedDate
}
setMaxAge()

async function handleSubmit() {
  try {
    const form = new FormData()

    for (const name in formData) {
      form.append(name, formData[name])
    }

    for (const i in images.value) {
      form.append(
        `photos${images.value.length > 1 ? '[]' : ''}`,
        images.value[i].file,
        images.value[i].file.name,
      )
    }

    const req = await fetch(`http://${location.hostname}:3000/auth/register`, {
      method: 'POST',
      body: form,
    })
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
  else if (message.search('biography') != -1) return 7
  else if (message.search('photos') != -1) return 8
}

function focusInput(code) {
  if (code < 3) step.value.setStep(0)
  else if (code >= 3 && code < 6) step.value.setStep(1)
  else if (code >= 6 && code < 7) step.value.setStep(2)
  else if (code == 7) step.value.setStep(3)
  else step.value.setStep(4)

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
          <div class="form-control mt-4">
            <TagSelector
              v-model="formData.tags"
              class="overflow-y-auto max-h-72"
            />
          </div>
        </form>
      </template>

      <template #step-4>
        <form>
          <div class="form-control">
            <label class="label">
              <span class="label-text"
                >Select up to {{ 5 - images.length }} photos</span
              >
            </label>
            <div class="grid grid-cols-3 gap-4">
              <div
                v-if="images.length < 5"
                class="relative w-full h-40 border-2 border-primary rounded flex justify-center items-center cursor-pointer"
              >
                <svg
                  class="w-9 stroke-primary"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g stroke-width="0"></g>
                  <g stroke-linecap="round" stroke-linejoin="round"></g>
                  <g>
                    <path
                      d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15"
                      stroke-width="1.5"
                      stroke-linecap="round"
                    ></path>
                    <path
                      d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                      stroke-width="1.5"
                      stroke-linecap="round"
                    ></path>
                  </g>
                </svg>
                <input
                  @change="handleFileUpload"
                  type="file"
                  class="w-full h-full opacity-0 absolute cursor-pointer"
                  multiple
                />
              </div>
              <div
                class="relative w-full h-40 border-2 border-primary rounded flex justify-center items-center cursor-pointer"
                v-for="(item, index) in images"
              >
                <img
                  class="object-cover w-full h-full"
                  alt="Image"
                  :src="item.url"
                />
                <div
                  @click="handleRemoveFileUpload(index)"
                  class="absolute opacity-0 w-full h-full bg-primary hover:opacity-55 flex items-center justify-center"
                >
                  <svg
                    class="w-9 stroke-white hover:opacity-100"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g stroke-width="0"></g>
                    <g
                      id="SVGRepo_tracerCarrier"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    ></g>
                    <g>
                      <path
                        d="M15 12H9"
                        stroke-width="1.5"
                        stroke-linecap="round"
                      ></path>
                      <path
                        d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                        stroke-width="1.5"
                        stroke-linecap="round"
                      ></path>
                    </g>
                  </svg>
                </div>
              </div>
            </div>
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

<template>
  <main v-if="skeleton">
    <div
      class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-muted px-2"
    >
      <div class="card bg-background w-full max-w-sm shrink-0 shadow-2xl">
        <div class="skeleton card-body h-24"></div>
      </div>
    </div>
  </main>

  <main
    v-else
    class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-muted px-2"
  >
    <div class="card bg-background w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <form v-if="pageState == 'goodToken'" @submit.prevent="changePassword">
          <div v-if="successed" class="toast toast-top toast-right">
            <div class="alert alert-success">
              <span>{{ successContent }}</span>
            </div>
          </div>
          <div v-if="successed == false" class="toast toast-top toast-right">
            <div class="alert alert-error">
              <span>{{ errorContent }}</span>
            </div>
          </div>

          <h3 class="text-3xl font-bold my-5">Create a new password !</h3>
          <div class="form-control">
            <label class="label">
              <span class="label-text">New password</span>
            </label>
            <label class="input input-bordered flex items-center gap-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-4 w-4 opacity-70"
              >
                <path
                  fill-rule="evenodd"
                  d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                  clip-rule="evenodd"
                />
              </svg>
              <input
                v-model="newPassword"
                type="password"
                class="grow"
                placeholder="********"
                required
              />
            </label>
            <label class="label mt-2">
              <span class="label-text">Confirm new password</span>
            </label>
            <label class="input input-bordered flex items-center gap-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-4 w-4 opacity-70"
              >
                <path
                  fill-rule="evenodd"
                  d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                  clip-rule="evenodd"
                />
              </svg>
              <input
                v-model="confirmPassword"
                type="password"
                class="grow"
                placeholder="********"
                required
              />
            </label>
          </div>
          <div class="flex justify-end mt-3 w-full">
            <button class="btn btn-outline btn-success ml-2" type="submit">
              Send
            </button>
          </div>
        </form>

        <span v-if="pageState == 'badToken'" class="text-destructive"
          >You don't have right to change this password or your link has
          expired</span
        >

        <span v-if="pageState == 'updatePwdSuccess'" class="text-success"
          >Your password has been successfully changed !</span
        >
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { Api } from '@/utils/api'
import { ref } from 'vue'

const pageState = ref<'badToken' | 'goodToken' | 'updatePwdSuccess'>('badToken')
const skeleton = ref(true)
const successed = ref<boolean | null>(null)
const errorContent = ref('')
const successContent = ref('')
const tokenPwd = ref<string>()

const newPassword = ref<string>()
const confirmPassword = ref<string>()

const urlParams = new URLSearchParams(window.location.search)

function printBack(state: boolean, info: string) {
  if (state == false) errorContent.value = info
  else successContent.value = info
  successed.value = state
  setTimeout(() => {
    successed.value = null
  }, 2500)
}

async function changePassword() {
  if (newPassword.value != confirmPassword.value) {
    printBack(false, 'the password need to be same')
  } else {
    const info = {
      username: urlParams.get('user'),
      newPassword: newPassword.value,
      confirmPassword: confirmPassword.value,
      token: tokenPwd.value,
    }
    const req = await Api.post('forgot/change-password').send(
      info as unknown as Record<string, unknown>,
    )
    const data = (await req.json()) as { success: boolean; error?: string }
    if (data.success) {
      printBack(true, 'The password is changed')
      pageState.value = 'updatePwdSuccess'
    } else printBack(false, data.error ?? 'Unknown error')
  }
  newPassword.value = ''
  confirmPassword.value = ''
}

const checkToken = async () => {
  const info = {
    username: urlParams.get('user'),
    token: urlParams.get('token'),
  }
  const req = await Api.post('forgot/token-verify').send(
    info as unknown as Record<string, unknown>,
  )
  const data = (await req.json()) as { success: boolean; token?: string }
  if (data.success) {
    pageState.value = 'goodToken'
    tokenPwd.value = data.token
  } else pageState.value = 'badToken'
  skeleton.value = false
}
checkToken()
</script>

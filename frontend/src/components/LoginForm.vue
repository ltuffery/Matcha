<script setup>
import router from '@/router'
import { login } from '@/services/auth'
import { ref } from 'vue'
import { Api } from '@/utils/api'
import FeedbackToast from '@/components/FeedbackToast.vue'
import { connectSocket } from '@/plugins/socket.js'

let username = ref(''),
  password = ref('')

const forgotPwd = ref(false),
  email = ref()

const toastsRef = ref()

function forgotSwitch() {
  forgotPwd.value = !forgotPwd.value
}

async function forgetPassword(e) {
  const response = await Api.post('forgot/credencial').send({
    email: email.value,
  })
  email.value = ''
  if (response.status === 200) {
    toastsRef.value.addSuccess('Email sended !')
  } else {
    toastsRef.value.addError('Error !')
  }
}

function loginUserAccount(e) {
  e.preventDefault()

  login(username.value, password.value).then(res => {
    if (res == null) {
      return
    }

    if (res.status === undefined) {
      navigator.geolocation.getCurrentPosition(
        loc => {
          Api.put('/users/me/localisation').send({
            lat: loc.coords.latitude,
            lon: loc.coords.longitude,
          })
        },
        err => {
          Api.put('/users/me/localisation').send()
        },
      )
      router.push({ name: 'main' })
      connectSocket()
    } else {
      switch (res.status) {
        case 400:
          toastsRef.value.addError('Bad credencials')
          break
        case 401:
          toastsRef.value.addWarning("Your email isn't verifyed")
          break
        default:
          toastsRef.value.addError('Unexpected error occured')
          break
      }
    }

    password.value = ''
  })
}
</script>

<template>
  <FeedbackToast posX="end" ref="toastsRef" class="h-2/6" />
  <form v-if="forgotPwd" @submit.prevent="forgetPassword">
    <h3 class="text-3xl font-bold my-5">Forgot credencial</h3>
    <span class=""
      >Forgot your username or password ? <br />Recive and change it by mail
      :</span
    >
    <div class="form-control">
      <label class="input input-bordered flex items-center gap-2 mt-2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 16 16"
          fill="currentColor"
          class="h-4 w-4 opacity-70"
        >
          <path
            d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"
          />
          <path
            d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"
          />
        </svg>
        <input
          v-model="email"
          type="email"
          class="grow"
          placeholder="Email"
          required
        />
      </label>
    </div>
    <div class="flex justify-between mt-6">
      <button class="btn btn-primary w-2/6" type="button" @click="forgotSwitch">
        Go back
      </button>
      <button class="btn btn-outline btn-success ml-2 w-2/6" type="submit">
        Send
      </button>
    </div>
  </form>

  <form v-else @submit="loginUserAccount">
    <h3 class="text-3xl font-bold my-5">Login !</h3>
    <div class="form-control">
      <label class="label">
        <span class="label-text">Username</span>
      </label>
      <input
        v-model="username"
        type="text"
        placeholder="username"
        class="input input-bordered"
        required
      />
    </div>
    <div class="form-control">
      <label class="label">
        <span class="label-text">Password</span>
      </label>
      <input
        v-model="password"
        type="password"
        placeholder="password"
        class="input input-bordered"
        required
      />
    </div>
    <div class="flex justify-end mt-3 w-full">
      <span class="cursor-pointer hover:underline" @click="forgotSwitch"
        >Forgot credencial ?</span
      >
    </div>
    <div class="form-control mt-6">
      <button class="btn btn-primary" type="submit">Login</button>
    </div>
  </form>
</template>

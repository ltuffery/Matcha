<script setup>
import { ref } from 'vue'
import { Api } from '@/utils/api'

let username = ref(''),
  password = ref('')

const forgotPwd = ref(false),
      email = ref(),
      successed = ref(null)

function forgotSwitch()
{
  forgotPwd.value = !forgotPwd.value;
}

function printBack(state)
{
  successed.value = state
  setTimeout(function() {
    successed.value = null
}, 2000);
}

async function forgetPassword(e)
{
  const response = await Api.post('forgot/credencial').send({email: email.value})
  email.value = ""
  if (response.status == 200)
  {
    printBack(true)
  }
  else
  {
    printBack(false)
  }
}

function loginUserAccount(e) {
  e.preventDefault()

  fetch('http://localhost:3000/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      username: username.value,
      password: password.value,
    }),
  })
    .then(res => res.json())
    .then(console.log)
}
</script>

<template>
  <form v-if="forgotPwd" @submit.prevent="forgetPassword">

  <div v-if="successed" class="toast toast-top toast-right">
    <div class="alert alert-success">
      <span>Email sended !</span>
    </div>
  </div>
  <div v-if="successed == false" class="toast toast-top toast-right">
    <div class="alert alert-error">
      <span>Error !</span>
    </div>
  </div>

    <h3 class="text-3xl font-bold my-5">Forgot !</h3>
    <span class="">Forgot your username or password ? </br>Recive and change it by mail :</span>
    <div class="form-control">
      <label class="input input-bordered flex items-center gap-2 mt-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="h-4 w-4 opacity-70">
            <path
              d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
            <path
              d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
          </svg>
          <input v-model="email" type="email" class="grow" placeholder="Email" required />
        </label>
    </div>
    <div class="flex justify-end mt-3 w-full">
        <button class="btn btn-outline btn-success ml-2" type="submit">Send</button>
    </div>
    <div class="form-control mt-6">
      <button class="btn btn-primary" type="button" @click="forgotSwitch">Go back</button>
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
        <span class="cursor-pointer hover:underline" @click="forgotSwitch">Forgot credencial ? 🥲</span>
    </div>
    <div class="form-control mt-6">
      <button class="btn btn-primary" type="submit">Login</button>
    </div>
  </form>
</template>

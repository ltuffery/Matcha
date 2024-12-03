<script setup>
import { ref } from 'vue'

let username = ref(''),
  password = ref('')

const forgotPwd = ref(false),
      email = ref()

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
  <dialog id="my_modal_1" class="modal">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Forgot Password</h3>
      <h4 class="mt-6">Enter the email of your account</h4>
      <div class="flex items-center mt-3">
        <label class="input input-bordered flex items-center gap-2">
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
          <input type="text" class="grow" placeholder="Email" />
        </label>

        <button class="btn btn-outline btn-success ml-2">Send</button>
      </div>
      <div class="modal-action">
        <form method="dialog">
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>

  <form v-if="forgotPwd" @submit="forgetPassword">
    <div class="form-control">
      <label class="label">
        <span class="label-text">Enter the email of your account</span>
      </label>
      <label class="input input-bordered flex items-center gap-2">
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
          <input v-model="email" type="email" class="grow" placeholder="Email" />
        </label>
    </div>
    <div class="flex justify-end mt-3 w-full">
        <button class="btn btn-outline btn-success ml-2" type="submit">Send</button>
    </div>
    <div class="form-control mt-6">
      <button class="btn btn-primary">Go back</button>
    </div>
  </form>

  <form v-else @submit="loginUserAccount">
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
        <!-- <span class="cursor-pointer hover:underline">Forgot password ? 🥲</span> -->
        <span class="cursor-pointer hover:underline" onclick="my_modal_1.showModal()">Forgot password ? 🥲</span>
    </div>
    <div class="form-control mt-6">
      <button class="btn btn-primary" type="submit">Register</button>
    </div>
  </form>
</template>

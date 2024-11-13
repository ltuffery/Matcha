<script setup>
import { ref } from 'vue';

let username = ref(''), email = ref(''), password = ref('')

function createUserAccount(e) {
  e.preventDefault();

  fetch("http://localhost:3000/auth/register", {
    method: 'POST',
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      username: username.value,
      email: email.value,
      password: password.value,
    }),
    mode: "no-cors"
  })
    .then(res => res.json())
    .then(console.log)
}
</script>

<template>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <div role="tablist" class="tabs tabs-boxed">
          <a role="tab" class="tab tab-disabled">Login</a>
          <a role="tab" class="tab tab-active">Register</a>
        </div>
        <h3 class="text-3xl font-bold my-5">Register !</h3>
        <form @submit="createUserAccount">
          <div class="form-control">
            <label class="label">
              <span class="label-text">Username</span>
            </label>
            <input v-model="username" type="text" placeholder="username" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Email</span>
            </label>
            <input v-model="email" type="email" placeholder="email" class="input input-bordered" required />
          </div>
          <div class="form-control">
            <label class="label">
              <span class="label-text">Password</span>
            </label>
            <input v-model="password" type="password" placeholder="password" class="input input-bordered" required />
          </div>
          <div class="form-control mt-6">
            <button class="btn btn-primary" type="submit">Register</button>
          </div>
        </form>
      </div>
    </div>
  </main>
</template>

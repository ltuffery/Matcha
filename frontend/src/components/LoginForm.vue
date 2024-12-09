<script setup>
import { login } from '@/services/auth';
import { ref } from 'vue'

let username = ref(''),
  password = ref('')

function loginUserAccount(e) {
  e.preventDefault()

  login(username.value, password.value).then(console.log)
}
</script>

<template>
  <form @submit="loginUserAccount">
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
    <div class="form-control mt-6">
      <button class="btn btn-primary" type="submit">Register</button>
    </div>
  </form>
</template>

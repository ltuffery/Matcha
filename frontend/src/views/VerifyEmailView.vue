<template>
  <main v-if="skeleton">
    <div
      class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2"
    >
      <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="skeleton card-body h-24"></div>
      </div>
    </div>
  </main>

  <main
    v-else
    class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2"
  >
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <span v-if="responseOK">Your email have been verified</span>
        <span v-else>Bad email verify link, or email already verified.</span>
      </div>
    </div>
  </main>
</template>

<script setup>
import { Api } from '@/utils/api'
import { ref } from 'vue'

const responseOK = ref(false)
const skeleton = ref(true)

const checkToken = async () => {
  let urlParams = new URLSearchParams(window.location.search)

  let test = {
    username: urlParams.get('user'),
    token: urlParams.get('token'),
  }
  let req = await Api.post('email/token').send(test)
  req = await req.json()
  if (req.success) responseOK.value = true
  else responseOK.value = false
  skeleton.value = false
}
checkToken()
</script>

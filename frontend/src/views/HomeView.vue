<script setup>
</script>

<template>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center">
    <div class="bg-neutral p-6 rounded-box w-10/12 flex flex-col gap-7">
      <h3 class="text-3xl font-bold">Register !</h3>

      <form class="flex flex-col gap-5">
        <input type="text" placeholder="username" class="input input-bordered w-full" />
        <input type="email" placeholder="email" class="input input-bordered w-full" />
        <input type="password" placeholder="password" class="input input-bordered w-full" />
        <a href="#">J'ai deja un compte</a>
        <button class="btn btn-primary">Register</button>
      </form>
    </div>
  </main>
</template>

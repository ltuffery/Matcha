<script setup>
import router from '@/router/index.js'
</script>

<template>
  <div class="hero bg-base-200 min-h-screen">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold">404</h1>
        <p class="py-6">
          Sorry, we couldn't find the page you were looking for.
        </p>
        <button @click="router.push({ name: 'home' })" class="btn btn-primary">
          Back to Home
        </button>
      </div>
    </div>
  </div>
</template>

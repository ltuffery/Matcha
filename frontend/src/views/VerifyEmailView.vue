<template>
  <main class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2">
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
        <div class="card-body">
            Your email have been verified
        </div>
    </div>
  </main>
</template>

<script setup>
    let urlParams = new URLSearchParams(window.location.search);
    console.log(urlParams.get('user'));
    console.log(urlParams.get('token'));
</script>

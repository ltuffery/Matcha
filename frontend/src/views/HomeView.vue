<template>
  <main
    class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-base-200 px-2"
  >
    <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
      <div class="card-body">
        <Tabs>
          <Tab name="Login">
            <h3 class="text-3xl font-bold my-5">Login !</h3>
            <LoginForm />
          </Tab>
          <Tab name="Register">
            <h3 class="text-3xl font-bold my-5">Register !</h3>
            <RegisterFrom />
          </Tab>
        </Tabs>
      </div>
    </div>
  </main>
</template>

<script setup>
import RegisterFrom from '@/components/RegisterForm.vue'
import Tabs from '@/components/tabs/Tabs.vue'
import Tab from '@/components/tabs/Tab.vue'
import LoginForm from '@/components/LoginForm.vue'
</script>

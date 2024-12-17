<script setup lang="ts">
import UserSearchCard from '@/components/UserSearchCard.vue';
import { ref } from 'vue';

const search = ref('')
const users = ref([
  "Test1",
  "Test2",
  "Test3",
  "Test4",
])
</script>

<template>
  <div class="max-w-3xl m-auto pt-8">
    <label class="input input-bordered flex items-center gap-2">
      <input v-model="search" type="text" class="grow" placeholder="Search a user" />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 16 16"
        fill="currentColor"
        class="h-4 w-4 opacity-70">
        <path
          fill-rule="evenodd"
          d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
          clip-rule="evenodd" />
      </svg>
    </label>
    <div class="p-6 flex flex-col gap-5" v-if="search.length >= 5">
      <UserSearchCard v-for="user in users" :key="user" :username="user" />
    </div>
  </div>
</template>
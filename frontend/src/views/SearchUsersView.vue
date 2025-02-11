<script setup lang="ts">
import UserSearchCard from '@/components/UserSearchCard.vue'
import { Api } from '@/utils/api'
import { ref, watch } from 'vue'

const search = ref('')
const users = ref([])

const input = () => {
  if (search.value.length >= 2) {
    Api.get(`search/users?q=${search.value}`)
      .send()
      .then(res => res.json())
      .then(data => (users.value = data))
  } else {
    users.value = []
  }
}
</script>

<template>
  <div class="max-w-3xl h-full m-auto pt-8">
    <label class="input input-bordered flex items-center gap-2">
      <input
        @input="input"
        v-model="search"
        type="text"
        class="grow"
        placeholder="Search a user"
      />
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        fill="none"
        class="h-4 w-4 opacity-70"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            d="M18.5 18.5L22 22"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
          <path
            d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
        </g>
      </svg>
    </label>
    <div
      class="p-6 flex flex-col gap-5 border-2 rounded-box border-neutral-content mt-2"
      v-if="users.length"
    >
      <UserSearchCard
        v-for="user in users"
        :key="user"
        :username="user.username"
        :avatar="user.avatar"
      />
    </div>
  </div>
</template>

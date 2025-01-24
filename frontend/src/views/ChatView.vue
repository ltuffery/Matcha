<script setup>
import UserCard from '@/components/chat/UserCard.vue'
import { Api } from '@/utils/api'
import { ref } from 'vue'
import router from '@/router'

const matches = ref([])
const conversations = ref([])
const searchInput = ref()

Api.get('/users/me/matches')
  .send()
  .then(res => {
    if (res.status === 401) {
      return []
    }

    return res.json()
  })
  .then(data => {
    for (const user of data) {
      if (!user.last_message) matches.value.push(user)
      else conversations.value.push(user)
    }
    conversations.value.sort(
      (a, b) =>
        new Date(b.last_message.created_at) -
        new Date(a.last_message.created_at),
    )
  })

// matches.value.sort((a,b) => b.lastMessage?.created_at - a.lastMessage?.created_at)
function convClick(username) {
  router.push({ name: `conversation`, params: { username } })
}

function containSearch(user) {
  if (!searchInput.value || searchInput.value.length < 1) return true
  user = user.toLowerCase()
  console.log(user)
  if (user.includes(searchInput.value.toLowerCase())) return true
  return false
}
</script>

<template>
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="bg-base-200 h-full w-full max-w-3xl px-4">
      <!-- ########## List of users ########## -->

      <div class="flex flex-col h-full">
        <div class="pt-5">
          <label class="input input-bordered flex items-center gap-2">
            <input
              v-model="searchInput"
              type="text"
              class="grow"
              placeholder="Search"
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 16 16"
              fill="currentColor"
              class="h-4 w-4 opacity-70"
            >
              <path
                fill-rule="evenodd"
                d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                clip-rule="evenodd"
              />
            </svg>
          </label>
        </div>

        <div
          v-if="matches.length"
          class="flex gap-2 overflow-x-auto mt-3 h-36 items-center"
        >
          <div
            v-for="(content, index) in matches"
            :key="index"
            @click="convClick(content.username)"
            class="select-none cursor-pointer flex flex-col items-center"
          >
            <div class="flex-none mask mask-squircle w-20">
              <img
                class="object-cover rounded-lg"
                :src="content.avatar"
              />
            </div>
            {{ content.first_name }}
          </div>
        </div>

        <div class="divider"></div>

        <div
          v-if="!conversations.length"
          class="flex flex-col gap-7 w-full h-full justify-center items-center"
        >
          <span class="text-2xl">You don't have a match yet</span>
          <button
            @click="router.push({ name: 'main' })"
            class="btn btn-outline btn-primary"
          >
            On your matchs
          </button>
        </div>

        <div v-else class="flex flex-col gap-2 overflow-y-auto h-[90%]">
          <div
            v-for="(content, index) in conversations"
            :key="index"
            @click="convClick(content.username)"
          >
            <UserCard
              v-if="containSearch(content.first_name)"
              :label="content.unread"
              :lastMessage="content.last_message?.content"
              :firstName="content.first_name"
              :avatar="content.avatar"
              online="false"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

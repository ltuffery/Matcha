<script setup lang="ts">
import UserMatch from "@/components/chat/UserMatch.vue";
import Message from "@/components/chat/Message.vue";
import { Api } from "@/utils/api";
import {ref} from "vue";

const matches = ref([])

Api.get('/users/me/matches').send()
  .then(res => {
    if (res.status === 401) {
      return [1,1,1,1,1,1,1,1,1,1,1]
    }

    return res.json()
  })
  .then(data => {
    matches.value = data
  })
</script>

<template>
  <div class="relative flex max-w-4xl m-auto pt-8 rounded-box border-1 border-neutral-content min-h-screen">
    <div class="flex flex-col gap-2 p-4 border-r-1 w-full overflow-auto h-screen">
      <input type="text" placeholder="Search conversation" class="input input-bordered" />
      <UserMatch last-message="Last message..." username="Teste" v-for="match in matches" :key="match.id" />
    </div>

    <!-- Conversation box -->
    <div class="relative sm:flex flex-col hidden">
      <Message is-me message="disjosfghofdijgoifdjgoifdjgoidjfgoijdfoigjdfoijgiofdjgiofdjgodijfgoidjfgoijfdigo" />
      <Message message="Lorem" />
      <input type="text" placeholder="Hello message" class="input input-bordered w-full absolute bottom-8" />
    </div>
  </div>
</template>

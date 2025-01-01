<script setup lang="ts">
import UserMatch from "@/components/chat/UserMatch.vue";
import Message from "@/components/chat/Message.vue";
import { Api } from "@/utils/api";
import {ref} from "vue";

const matches = ref([])

Api.get('/users/me/matches').send()
  .then(res => res.json())
  .then(data => {
    matches.value = data
  })
</script>

<template>
  <div class="flex max-w-4xl m-auto pt-8 rounded-box border-1 border-neutral-content relative h-full min-h-screen">
    <div class="flex flex-col p-4 border-r-1" v-for="match in matches" :key="match.id">
      <UserMatch last-message="Last message..." username="Teste" />
    </div>

    <!-- Conversation box -->
    <div class="p-8 flex flex-col relative w-full">
      <Message is-me message="disjosfghofdijgoifdjgoifdjgoidjfgoijdfoigjdfoijgiofdjgiofdjgodijfgoidjfgoijfdigo" />
      <Message message="Lorem" />
      <input type="text" placeholder="Hello message" class="input input-bordered w-full absolute bottom-0 m-8" />
    </div>
  </div>
</template>

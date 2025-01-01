<template>
  <div class="flex max-w-4xl m-auto pt-8 rounded-box border-1 border-neutral-content relative">
    <div class="flex flex-col p-4 border-r-1">
      <UserMatch last-message="Last message..." username="Teste" />
    </div>

    <!-- Conversation box -->
    <div class="p-8 flex flex-col relative w-full">
      <Message is-me message="disjosfghofdijgoifdjgoifdjgoidjfgoijdfoigjdfoijgiofdjgiofdjgodijfgoidjfgoijfdigo" />
      <Message message="Lorem" />
    </div>
  </div>
</template>
<script setup lang="ts">
import UserMatch from "@/components/chat/UserMatch.vue";
import Message from "@/components/chat/Message.vue";
</script>

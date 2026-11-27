<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import PeopleList from '@/components/chat/PeopleList.vue'
import Conversation from '@/components/chat/Conversation.vue'
import { useChatStore } from '@/store/useChatStore'
import { getSocket } from '@/plugins/socket'
import type { Message } from '@/api/chat'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty'
import { MessagesCircleIcon } from '@lucide/vue'

const route = useRoute()
const chatStore = useChatStore()
const { matches } = storeToRefs(chatStore)

const selectedUser = computed(() => {
  const username = route.params.username as string | undefined
  if (!username) return null
  return matches.value.find(m => m.username === username) ?? null
})

const socket = getSocket()
const onCreateMessage = (message: Message) => chatStore.receiveMessage(message)
const onDeleteMessage = (message: { id: number }) =>
  chatStore.removeMessage(message.id)

onMounted(() => {
  chatStore.fetchMatches()
  socket?.on('create_message', onCreateMessage)
  socket?.on('delete_message', onDeleteMessage)
})

onBeforeUnmount(() => {
  socket?.off('create_message', onCreateMessage)
  socket?.off('delete_message', onDeleteMessage)
  chatStore.clearActiveConversation()
})
</script>

<template>
  <div class="w-full h-full flex gap-12 py-4">
    <div
      class="w-full h-full overflow-y-hidden max-w-sm border rounded-lg p-4 lg:flex"
      :class="{
        flex: route.params.username == null,
        hidden: route.params.username != null,
      }"
    >
      <PeopleList />
    </div>
    <div
      class="w-full min-w-0 min-h-0 flex-col lg:flex"
      :class="{
        flex: route.params.username != null,
        hidden: route.params.username == null,
      }"
    >
      <Conversation
        v-if="selectedUser"
        :key="selectedUser.username"
        :selected-user="selectedUser"
        class="min-w-0 overflow-x-hidden flex min-h-0"
      />
      <div v-else class="flex h-full min-h-0 flex-col">
        <Empty>
          <EmptyHeader>
            <EmptyMedia variant="icon">
              <MessagesCircleIcon />
            </EmptyMedia>
            <EmptyTitle>No conversation selected</EmptyTitle>
            <EmptyDescription>
              Select a conversation so that its content is displayed here
            </EmptyDescription>
          </EmptyHeader>
        </Empty>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {
  SendHorizontalIcon,
  MessagesCircleIcon,
  MessageCircleIcon,
  FaceSlightlySmilingPlus,
} from '@lucide/vue'
import { computed, onMounted, ref, watch } from 'vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupButton,
  InputGroupTextarea,
} from '@/components/ui/input-group'
import {
  MessageScroller,
  MessageScrollerButton,
  MessageScrollerContent,
  MessageScrollerItem,
  MessageScrollerProvider,
  MessageScrollerViewport,
} from '@/components/ui/message-scroller'
import { Button } from '@/components/ui/button'
import { CalendarPlusIcon, ChevronLeftIcon } from 'lucide-vue-next'
import MoreButton from '@/components/chat/header/MoreButton.vue'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty'
import { useRoute } from 'vue-router'
import type { MessageData, SmallUser } from '@/types'
import router from '@/router'
import { getSocket } from '@/plugins/socket'
import { useTypingStore } from '@/store/isTyping'
import { useMessagesStore } from '@/store/messages'
import { ApiClient } from '@/api/client'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import {
  EmojiPicker,
  EmojiPickerContent,
  EmojiPickerFooter,
  EmojiPickerSearch,
} from '@/components/ui/emoji-picker'
import { useShiftKeyWatcher } from '@/composable/useShiftKeyWatcher'
import Message from '@/components/chat/Message.vue'
import type { SelectedEmoji } from '@/components/ui/emoji-picker/emoji-picker.vue'

interface MessageGroup {
  isMe: boolean
  messages: MessageData[]
}

const route = useRoute()

const messagesStore = useMessagesStore()
// const messages = ref<MessageData[]>([])
const messages = computed(() =>
  messagesStore.getMessages(route.params.username as string),
)
const user = ref<SmallUser>()

const typingStore = useTypingStore()
const isOtherTyping = computed(() =>
  typingStore.isTyping(user.value?.username ?? ''),
)

const displayName = computed(
  () => user.value?.first_name + ' ' + user.value?.last_name,
)
const messageGroups = computed<MessageGroup[]>(() => {
  const groups: MessageGroup[] = []

  for (const message of messages.value) {
    const lastGroup = groups[groups.length - 1]
    const isMe = message.sender !== route.params.username

    if (lastGroup && lastGroup.isMe === isMe) {
      lastGroup.messages.push(message)
    } else {
      groups.push({ isMe: isMe, messages: [message] })
    }
  }

  return groups
})

const suggestMsg = ['Salut ! 👋', 'Comment ça va ?', 'On se lance un café ?']
const draft = ref('')

const fetchMessages = async () => {
  if (route.params.username === undefined) {
    return
  }

  const response = await ApiClient.get(
    `/users/me/matches/${route.params.username}`,
  )

  if (response.ok()) {
    const data: SmallUser & {
      messages: MessageData[]
    } = await response.json()

    user.value = {
      username: data.username,
      avatar: data.avatar,
      first_name: data.first_name,
      last_name: data.last_name,
    }
    // messages.value = data.messages
    messagesStore.setMessages(data.username, data.messages)
  }
}

function sendMessage(content?: string) {
  const text = (content ?? draft.value).trim()
  if (!text || !user.value?.username) return
  getSocket().emit('stop_typing', { to_username: user.value.username })
  messagesStore.sendMessage(user.value.username, text)
  draft.value = ''
}

let typingTimeoutMs: NodeJS.Timeout

function onChatInput() {
  getSocket()?.emit('typing', { to_username: user.value?.username })

  clearTimeout(typingTimeoutMs)
  typingTimeoutMs = setTimeout(() => {
    getSocket().emit('stop_typing', { to_username: user.value?.username })
  }, 2000)
}

const hoveredItem = ref<MouseEvent | null>(null)
const shiftPressed = useShiftKeyWatcher()
const selectedItem = computed(() =>
  shiftPressed.value ? hoveredItem.value : null,
)

const handleEmojiSelect = (emoji: SelectedEmoji) => {
  if (selectedItem.value == null) isOpen.value = false

  draft.value += emoji.emoji
}

const isOpen = ref(false)

watch(
  () => route.params.username,
  async () => {
    await fetchMessages()
  },
  { immediate: true },
)

onMounted(async () => {
  await fetchMessages()
})
</script>

<template>
  <div
    v-if="route.params.username === undefined"
    class="flex h-full min-h-0 flex-col"
  >
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
  <div v-else class="flex h-full min-h-0 flex-col">
    <header class="flex items-center gap-3 border-b px-4 py-3">
      <Button
        @click="router.push({ name: 'messages' })"
        variant="ghost"
        class="lg:hidden"
      >
        <ChevronLeftIcon />
      </Button>

      <Avatar class="size-10">
        <AvatarImage
          v-if="user?.avatar"
          :src="user?.avatar"
          :alt="user?.username"
        />
        <AvatarFallback>{{
          user?.username.charAt(0).toUpperCase()
        }}</AvatarFallback>
      </Avatar>
      <div class="min-w-0">
        <p class="truncate text-sm font-medium leading-tight">
          {{ displayName }}
        </p>
        <p class="text-muted-foreground truncate text-xs">
          {{ user?.username }}
        </p>
      </div>

      <div class="ml-auto flex items-center gap-1">
        <Button
          variant="ghost"
          size="icon"
          aria-label="Planifier un rendez-vous"
        >
          <CalendarPlusIcon class="size-5" />
        </Button>

        <MoreButton :username="user?.username" />
      </div>
    </header>

    <div class="min-h-0 flex-1">
      <MessageScrollerProvider
        v-if="messageGroups.length > 0"
        default-scroll-position="end"
        auto-scroll
      >
        <MessageScroller>
          <MessageScrollerViewport class="overflow-x-hidden">
            <MessageScrollerContent class="p-4 py-8">
              <MessageScrollerItem
                v-for="(group, groupIndex) in messageGroups"
                :key="groupIndex"
                :message-id="`group-${groupIndex}`"
                :scroll-anchor="group.isMe"
                class="[content-visibility:visible] focus-within:[content-visibility:visible]"
              >
                <Message :is-me="group.isMe" :messages="group.messages" />
              </MessageScrollerItem>
            </MessageScrollerContent>
          </MessageScrollerViewport>
          <MessageScrollerButton />
        </MessageScroller>
      </MessageScrollerProvider>

      <div v-else class="flex h-full flex-col justify-end gap-10 p-4">
        <div class="flex flex-1 flex-col items-center justify-center gap-3">
          <div
            class="bg-muted flex items-center justify-center rounded-full p-6"
          >
            <MessageCircleIcon class="h-8 w-8" />
          </div>
          <p class="text-2xl">No messages</p>
          <p class="text-muted-foreground text-sm">Post the first message!</p>
        </div>
        <div class="flex w-full gap-2 overflow-x-auto pb-1">
          <button
            v-for="(content, index) in suggestMsg"
            :key="index"
            class="bg-secondary text-secondary-foreground shrink-0 rounded-full px-4 py-2 text-sm"
            @click="sendMessage(content)"
          >
            {{ content }}
          </button>
        </div>
      </div>
    </div>

    <div
      v-if="isOtherTyping"
      class="shimmer px-4 pb-1 text-xs text-muted-foreground"
    >
      {{ user?.username }} is typing...
    </div>
    <form class="border-t p-3" @submit.prevent="sendMessage()">
      <InputGroup>
        <InputGroupTextarea
          v-model="draft"
          placeholder="Votre message"
          class="max-h-40"
          @input="onChatInput"
          @keydown.enter.exact.prevent="sendMessage()"
        />
        <InputGroupAddon align="inline-end">
          <Popover v-model:open="isOpen">
            <PopoverTrigger as-child>
              <InputGroupButton size="icon-sm" aria-label="Emoji">
                <FaceSlightlySmilingPlus />
                <span class="sr-only">Emoji</span>
              </InputGroupButton>
            </PopoverTrigger>
            <PopoverContent class="w-fit p-0 rounded-xl">
              <EmojiPicker
                class="h-85.5 border-none! shadow-none!"
                @emoji-select="handleEmojiSelect"
                @mouseenter="(event: MouseEvent) => (hoveredItem = event)"
                @mouseleave="hoveredItem = null"
              >
                <EmojiPickerSearch />
                <EmojiPickerContent />
                <EmojiPickerFooter />
              </EmojiPicker>
            </PopoverContent>
          </Popover>

          <InputGroupButton
            type="submit"
            size="icon-sm"
            aria-label="Envoyer"
            :disabled="!draft.trim()"
          >
            <SendHorizontalIcon />
            <span class="sr-only">Envoyer</span>
          </InputGroupButton>
        </InputGroupAddon>
      </InputGroup>
    </form>
  </div>
</template>

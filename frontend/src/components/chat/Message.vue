<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue'
import { Bubble, BubbleContent, BubbleReactions } from '@/components/ui/bubble'
import { ButtonGroup } from '@/components/ui/button-group'
import {
  Message as ShadcnMessage,
  MessageContent,
  MessageGroup,
} from '@/components/ui/message'
import { Button } from '@/components/ui/button'
import {
  EmojiPicker,
  EmojiPickerContent,
  EmojiPickerFooter,
  EmojiPickerSearch,
} from '@/components/ui/emoji-picker'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import { FaceSlightlySmilingPlus, Trash } from '@lucide/vue'
import type { SelectedEmoji } from '@/components/ui/emoji-picker/emoji-picker.vue'
import type { Message } from '@/api/chat'
import type { Reaction } from '@/api/reactions'
import { getSocket } from '@/plugins/socket'

export interface InternalReactionEmit {
  message: Message
  emoji: string
  active: boolean
}

interface GroupedReaction {
  emoji: string
  count: number
  mine: boolean
}

const props = defineProps<{
  isMe: boolean
  messages: Message[]
}>()

const emit = defineEmits<{
  (e: 'react', payload: InternalReactionEmit): void
  (e: 'delete', messageId: number): void
}>()

const hoveredMessageId = ref<number | null>(null)
const openMessageId = ref<number | null>(null)

const reactions = ref<Record<number, Reaction[]>>({})

watch(
  () => props.messages,
  msgs => {
    const map: Record<number, Reaction[]> = {}
    msgs.forEach(m => {
      map[m.id] = [...(m.reactions ?? [])]
    })
    reactions.value = map
  },
  { immediate: true, deep: true },
)

const isToolbarVisible = (id: number) =>
  hoveredMessageId.value === id || openMessageId.value === id

const groupedReactions = (id: number): GroupedReaction[] => {
  const groups = new Map<string, GroupedReaction>()
  for (const r of reactions.value[id] ?? []) {
    const g = groups.get(r.emoji) ?? { emoji: r.emoji, count: 0, mine: false }
    g.count++
    if (r.is_me) g.mine = true
    groups.set(r.emoji, g)
  }
  return [...groups.values()]
}

const toggleReaction = (emoji: string, message: Message) => {
  const list = reactions.value[message.id] ?? []
  const mine = list.find(r => r.is_me)
  const others = list.filter(r => !r.is_me)

  const active = mine?.emoji !== emoji

  reactions.value[message.id] = active
    ? [...others, { username: '', message_id: message.id, emoji, is_me: true }]
    : others

  emit('react', { message, emoji, active })
}

const handleEmojiSelect = (emoji: SelectedEmoji, message: Message) => {
  toggleReaction(emoji.emoji, message)
  setTimeout(() => {
    openMessageId.value = null
  }, 0)
}

const isSameAuthor = (r: Reaction, incoming: Reaction) =>
  r.username === incoming.username || (incoming.is_me && r.is_me)

const onCreateReaction = (reaction: Reaction) => {
  if (!(reaction.message_id in reactions.value)) return

  const list = reactions.value[reaction.message_id] ?? []

  reactions.value[reaction.message_id] = [
    ...list.filter(r => !isSameAuthor(r, reaction)),
    reaction,
  ]
}

const onDeleteReaction = (reaction: Reaction) => {
  if (!(reaction.message_id in reactions.value)) return

  reactions.value[reaction.message_id] = (
    reactions.value[reaction.message_id] ?? []
  ).filter(r => !isSameAuthor(r, reaction))
}

const socket = getSocket()
socket?.on('create_reaction', onCreateReaction)
socket?.on('delete_reaction', onDeleteReaction)

onBeforeUnmount(() => {
  socket?.off('create_reaction', onCreateReaction)
  socket?.off('delete_reaction', onDeleteReaction)
})
</script>

<template>
  <MessageGroup>
    <ShadcnMessage :align="isMe ? 'end' : 'start'">
      <MessageContent class="flex flex-col gap-1">
        <Bubble
          v-for="message in messages"
          :key="message.id"
          :variant="isMe ? 'default' : 'secondary'"
          class="relative"
          :class="{ 'mb-4': groupedReactions(message.id).length > 0 }"
          @mouseenter="hoveredMessageId = message.id"
          @mouseleave="hoveredMessageId = null"
        >
          <ButtonGroup
            v-show="isToolbarVisible(message.id)"
            class="absolute bg-popover -top-4 right-2 z-50 rounded-md shadow-sm"
          >
            <Button
              variant="outline"
              class="bg-popover hover:bg-accent"
              @click="toggleReaction('❤️', message)"
            >
              ❤️
            </Button>

            <Popover
              :open="openMessageId === message.id"
              @update:open="val => (openMessageId = val ? message.id : null)"
            >
              <PopoverTrigger as-child>
                <Button
                  variant="outline"
                  class="bg-popover hover:bg-accent"
                  aria-label="Emoji"
                >
                  <FaceSlightlySmilingPlus />
                  <span class="sr-only">Emoji</span>
                </Button>
              </PopoverTrigger>

              <PopoverContent class="w-fit p-0 rounded-xl">
                <EmojiPicker
                  class="h-85.5 border-none! shadow-none!"
                  @emoji-select="
                    (emoji: SelectedEmoji) => handleEmojiSelect(emoji, message)
                  "
                >
                  <EmojiPickerSearch />
                  <EmojiPickerContent />
                  <EmojiPickerFooter />
                </EmojiPicker>
              </PopoverContent>
            </Popover>

            <Button
              v-if="isMe"
              variant="outline"
              class="bg-popover hover:bg-accent"
              @click="emit('delete', message.id)"
            >
              <Trash />
            </Button>
          </ButtonGroup>

          <BubbleContent class="whitespace-pre-line">{{
            message.content
          }}</BubbleContent>

          <!-- Reactions -->
          <BubbleReactions
            v-if="groupedReactions(message.id).length > 0"
            class="mb-1"
          >
            <span
              v-for="g in groupedReactions(message.id)"
              :key="g.emoji"
              @click="toggleReaction(g.emoji, message)"
            >
              {{ g.emoji }}
              <span v-if="g.count > 1" class="text-xs">{{ g.count }}</span>
            </span>
          </BubbleReactions>
        </Bubble>
      </MessageContent>
    </ShadcnMessage>
  </MessageGroup>
</template>

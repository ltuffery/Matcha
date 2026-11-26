<script setup lang="ts">
import { ref } from 'vue'
import { Bubble, BubbleContent, BubbleReactions } from '@/components/ui/bubble'
import { ButtonGroup } from '@/components/ui/button-group'
import {
  Message as ShadcnMessage,
  MessageContent,
  MessageGroup,
} from '@/components/ui/message'
import type { MessageData } from '@/types'
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
import { FaceSlightlySmilingPlus } from '@lucide/vue'

interface SelectedEmoji {
  emoji: string
  label: string
}

const props = defineProps<{
  isMe: boolean
  messages: MessageData[]
}>()

const emit = defineEmits<{
  (
    e: 'react',
    payload: { message: MessageData; emoji: string; active: boolean },
  ): void
}>()

const hoveredMessageIndex = ref<number | null>(null)
const openMessageIndex = ref<number | null>(null)
const reactions = ref<Record<number, string | null>>({})

const isToolbarVisible = (msgIndex: number) =>
  hoveredMessageIndex.value === msgIndex || openMessageIndex.value === msgIndex

const toggleReaction = (emoji: string, msgIndex: number) => {
  const exist = reactions.value[msgIndex] == emoji

  reactions.value[msgIndex] = exist ? null : emoji

  emit('react', {
    message: props.messages[msgIndex],
    emoji,
    active: !exist,
  })
}

const handleEmojiSelect = (emoji: SelectedEmoji, msgIndex: number) => {
  toggleReaction(emoji.emoji, msgIndex)
  setTimeout(() => {
    openMessageIndex.value = null
  }, 0)
}
</script>

<template>
  <MessageGroup>
    <ShadcnMessage :align="isMe ? 'end' : 'start'">
      <MessageContent class="flex flex-col gap-1">
        <Bubble
          v-for="(message, msgIndex) in messages"
          :key="msgIndex"
          :variant="isMe ? 'default' : 'secondary'"
          class="relative"
          :class="{ 'mb-4': reactions[msgIndex]?.length }"
          @mouseenter="hoveredMessageIndex = msgIndex"
          @mouseleave="hoveredMessageIndex = null"
        >
          <ButtonGroup
            v-show="isToolbarVisible(msgIndex)"
            class="absolute bg-popover -top-4 right-2 z-50 rounded-md shadow-sm"
          >
            <Button
              variant="outline"
              class="bg-popover hover:bg-accent"
              @click="toggleReaction('❤️', msgIndex)"
            >
              ❤️
            </Button>

            <Popover
              :open="openMessageIndex === msgIndex"
              @update:open="val => (openMessageIndex = val ? msgIndex : null)"
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
                    (emoji: SelectedEmoji) => handleEmojiSelect(emoji, msgIndex)
                  "
                >
                  <EmojiPickerSearch />
                  <EmojiPickerContent />
                  <EmojiPickerFooter />
                </EmojiPicker>
              </PopoverContent>
            </Popover>
          </ButtonGroup>

          <BubbleContent class="whitespace-pre-line">{{
            message.content
          }}</BubbleContent>

          <!-- Réactions -->
          <BubbleReactions
            v-if="reactions[msgIndex] != null"
            @click="toggleReaction(reactions[msgIndex], msgIndex)"
            class="mb-1"
          >
            {{ reactions[msgIndex] }}
          </BubbleReactions>
        </Bubble>
      </MessageContent>
    </ShadcnMessage>
  </MessageGroup>
</template>

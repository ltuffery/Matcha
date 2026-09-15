<script setup lang="ts">
import { SendHorizontalIcon } from 'lucide-vue-next'
import { computed, ref } from 'vue'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Bubble, BubbleContent } from '@/components/ui/bubble'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupButton,
  InputGroupTextarea,
} from '@/components/ui/input-group'
import { Message, MessageContent, MessageGroup } from '@/components/ui/message'
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

interface MockMessage {
  content: string
  isMe: boolean
}

interface MessageGroup {
  isMe: boolean
  messages: MockMessage[]
}

const mockUser = {
  username: 'lea.martin',
  first_name: 'Léa',
  last_name: 'Martin',
  avatar: '',
}

const displayName = 'Léa Martin'

const mockMessages = ref<MockMessage[]>([
  { content: "Salut ! J'ai vu qu'on avait matché 😄", isMe: false },
  { content: "Hello ! Oui trop cool, j'adore ton profil", isMe: true },
  { content: 'Merci ! Tu fais quoi dans la vie ?', isMe: false },
  { content: 'Je suis développeur, et toi ?', isMe: true },
  { content: 'Je suis photographe, Aussi', isMe: false },
  { content: 'Je suis photographe, freelance', isMe: false },
  { content: 'Je suis photographe, freelance', isMe: false },
  { content: 'Je suis photographe, freelance', isMe: false },
  { content: 'Je suis photographe, freelance', isMe: false },
  {
    content: 'Ah trop stylé ça, tu photographies quoi principalement ?',
    isMe: true,
  },
  { content: 'Surtout du portrait et un peu de mariage', isMe: false },
  {
    content: "On devrait se voir autour d'un café un de ces jours ☕",
    isMe: true,
  },
  { content: 'Avec plaisir, tu es dispo ce weekend ?', isMe: false },
])

const messageGroups = computed<MessageGroup[]>(() => {
  const groups: MessageGroup[] = []

  for (const message of mockMessages.value) {
    const lastGroup = groups[groups.length - 1]

    if (lastGroup && lastGroup.isMe === message.isMe) {
      lastGroup.messages.push(message)
    } else {
      groups.push({ isMe: message.isMe, messages: [message] })
    }
  }

  return groups
})

const suggestMsg = ['Salut ! 👋', 'Comment ça va ?', 'On se lance un café ?']

const draft = ref('')

const hasMessages = ref(true) // passe à false pour voir l'état vide

function sendMessage(content?: string) {
  const text = (content ?? draft.value).trim()
  if (!text) return
  mockMessages.value.push({ content: text, isMe: true })
  draft.value = ''
}
</script>

<template>
  <div class="flex h-full min-h-0 flex-col">
    <header class="flex items-center gap-3 border-b px-4 py-3">
      <Button variant="ghost" class="lg:hidden">
        <ChevronLeftIcon />
      </Button>

      <Avatar class="size-10">
        <AvatarImage
          v-if="mockUser.avatar"
          :src="mockUser.avatar"
          :alt="mockUser.username"
        />
        <AvatarFallback>{{
          mockUser.username.charAt(0).toUpperCase()
        }}</AvatarFallback>
      </Avatar>
      <div class="min-w-0">
        <p class="truncate text-sm font-medium leading-tight">
          {{ displayName }}
        </p>
        <p class="text-muted-foreground truncate text-xs">
          {{ mockUser.username }}
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

        <MoreButton />
      </div>
    </header>

    <div class="min-h-0 flex-1">
      <MessageScrollerProvider
        v-if="hasMessages && mockMessages.length"
        default-scroll-position="last-anchor"
      >
        <MessageScroller>
          <MessageScrollerViewport class="overflow-x-hidden">
            <MessageScrollerContent class="p-4 py-8">
              <MessageScrollerItem
                v-for="(group, groupIndex) in messageGroups"
                :key="groupIndex"
                :message-id="`group-${groupIndex}`"
                :scroll-anchor="group.isMe"
              >
                <MessageGroup>
                  <Message :align="group.isMe ? 'end' : 'start'">
                    <MessageContent class="flex flex-col gap-1">
                      <Bubble
                        v-for="(message, msgIndex) in group.messages"
                        :key="msgIndex"
                        :variant="group.isMe ? 'default' : 'secondary'"
                      >
                        <BubbleContent class="whitespace-pre-line">{{
                          message.content
                        }}</BubbleContent>
                      </Bubble>
                    </MessageContent>
                  </Message>
                </MessageGroup>
              </MessageScrollerItem>
            </MessageScrollerContent>
          </MessageScrollerViewport>
          <MessageScrollerButton />
        </MessageScroller>
      </MessageScrollerProvider>

      <div v-else class="flex h-full flex-col justify-end gap-10 p-4">
        <div class="flex flex-1 flex-col items-center justify-center gap-3">
          <div
            class="bg-muted flex size-24 items-center justify-center rounded-full text-4xl"
          >
            💬
          </div>
          <p class="text-2xl">Aucun message</p>
          <p class="text-muted-foreground text-sm">
            Dis bonjour à {{ displayName }} — choisis une suggestion
          </p>
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

    <form class="border-t p-3" @submit.prevent="sendMessage()">
      <InputGroup>
        <InputGroupTextarea
          v-model="draft"
          placeholder="Votre message"
          class="max-h-40"
          @keydown.enter.exact.prevent="sendMessage()"
        />
        <InputGroupAddon align="inline-end">
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

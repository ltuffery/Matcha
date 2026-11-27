<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { SearchIcon, UserGroupIcon } from '@lucide/vue'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
} from '@/components/ui/input-group'
import {
  Item,
  ItemContent,
  ItemDescription,
  ItemGroup,
  ItemMedia,
  ItemSeparator,
  ItemTitle,
} from '@/components/ui/item'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty'
import { Separator } from '@/components/ui/separator'
import { ScrollArea } from '@/components/ui/scroll-area'
import { useTypingStore } from '@/store/isTyping'
import { useChatStore } from '@/store/useChatStore'
import type { MatchUser } from '@/api/chat'

const route = useRoute()
const router = useRouter()
const typingStore = useTypingStore()
const { matches: allMatches } = storeToRefs(useChatStore())

const search = ref('')

const matches = computed(() =>
  allMatches.value.filter(m => m.last_message == null),
)
const peoples = computed(() =>
  allMatches.value.filter(m => m.last_message != null),
)

const filteredPeople = computed(() => {
  const query = search.value.trim().toLowerCase()
  return peoples.value.filter(person =>
    person.first_name?.toLowerCase().includes(query),
  )
})

function convClick(user: MatchUser) {
  router.push({ name: 'messages.user', params: { username: user.username } })
}
</script>

<template>
  <div class="w-full h-full flex flex-col gap-2">
    <InputGroup>
      <InputGroupInput placeholder="Search..." v-model="search" />
      <InputGroupAddon>
        <SearchIcon />
      </InputGroupAddon>
      <InputGroupAddon v-if="search.length > 0" align="inline-end">
        {{ filteredPeople.length }}
        results
      </InputGroupAddon>
    </InputGroup>

    <div v-if="matches.length" class="flex flex-col gap-2 mt-4 overflow-x-auto">
      <div class="flex gap-4 overflow-x-auto items-center">
        <div
          v-for="(content, index) in matches"
          :key="index"
          @click="convClick(content)"
          class="select-none cursor-pointer flex flex-col items-center"
        >
          <Avatar class="h-12 w-12 cursor-pointer">
            <AvatarImage :src="content.avatar ?? ''" :alt="content.username" />
            <AvatarFallback>{{
              content.username.charAt(0).toUpperCase()
            }}</AvatarFallback>
          </Avatar>
          {{ content.first_name }}
        </div>
      </div>

      <Separator />
    </div>

    <Empty v-if="!peoples.length" class="flex">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <UserGroupIcon />
        </EmptyMedia>
        <EmptyTitle>No one here</EmptyTitle>
        <EmptyDescription v-if="!peoples.length && !matches.length">
          You don't have a match yet
        </EmptyDescription>
        <EmptyDescription v-else>
          No conversations have started
        </EmptyDescription>
      </EmptyHeader>
    </Empty>

    <div v-else class="flex-1 min-h-0">
      <ScrollArea class="h-full w-full">
        <ItemGroup>
          <template
            v-for="(person, index) in filteredPeople"
            :key="person.username"
          >
            <Item
              @click="convClick(person)"
              class="group relative flex items-center gap-3 transition-all duration-200 ease-out hover:bg-muted/50 cursor-pointer rounded-lg pl-4 pr-3 py-2.5 overflow-hidden before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-0 before:w-0.75 before:rounded-r-full before:bg-primary before:transition-all before:duration-300 before:ease-out hover:before:h-4/5"
              :class="{
                'bg-muted before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-0.75 before:rounded-r-full before:bg-primary before:transition-all before:duration-300 before:ease-out before:h-4/5':
                  route.params.username === person.username,
              }"
            >
              <ItemMedia>
                <Avatar>
                  <AvatarImage
                    :src="person.avatar ?? 'https://github.com/shadcn.png'"
                  />
                  <AvatarFallback>{{
                    person.username.charAt(0)
                  }}</AvatarFallback>
                </Avatar>
              </ItemMedia>
              <ItemContent class="gap-1">
                <ItemTitle
                  >{{ person.first_name }} {{ person.last_name }}</ItemTitle
                >
                <ItemDescription
                  class="shimmer"
                  v-if="typingStore.isTyping(person.username)"
                >
                  {{ person.username }} is typing...</ItemDescription
                >
                <ItemDescription v-else>{{
                  person.last_message?.content
                }}</ItemDescription>
              </ItemContent>
            </Item>
            <ItemSeparator
              class="my-0!"
              v-if="index < filteredPeople.length - 1"
            />
          </template>
        </ItemGroup>
      </ScrollArea>
    </div>
  </div>
</template>

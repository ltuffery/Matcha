<script setup lang="ts">
import UserCard from '@/components/chat/UserCard.vue'
import { Api } from '@/utils/api'
import { ref } from 'vue'
import router from '@/router'
import Empty from '@/components/core/Empty.vue'
import type { User } from '@/types'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { SearchIcon } from 'lucide-vue-next'
import {
  InputGroup,
  InputGroupAddon,
  InputGroupInput,
} from '@/components/ui/input-group'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  Item,
  ItemContent,
  ItemDescription,
  ItemGroup,
  ItemMedia,
  ItemSeparator,
  ItemTitle,
} from '@/components/ui/item'

interface ChatUser {
  username: string
  email?: string
  first_name?: string
  last_name?: string
  gender?: 'M' | 'F' | 'O'
  biography?: string
  birthday?: string
  age?: number
  photos?: string[]
  tags?: string[]
  avatar?: string
  distance?: number
  fame_rating?: number
  me?: boolean
  last_message?: { content: string; created_at: string }
  unread?: number
}

const matches = ref<ChatUser[]>([])
const people = ref<ChatUser[]>([])
const searchInput = ref<string>('')

Api.get('/users/me/matches')
  .send()
  .then(res => {
    if (res.status === 401) {
      return []
    }

    return res.json()
  })
  .then((data: ChatUser[]) => {
    for (const user of data) {
      if (!user.last_message) matches.value.push(user)
      else people.value.push(user)
    }
    people.value.sort(
      (a, b) =>
        new Date(b.last_message!.created_at).getTime() -
        new Date(a.last_message!.created_at).getTime(),
    )
  })

function convClick(username: string) {
  router.push({ name: 'conversation', params: { username } })
}

function containSearch(user: string): boolean {
  if (!searchInput.value || searchInput.value.length < 1) return true

  return user.toLowerCase().includes(searchInput.value.toLowerCase())
}
</script>

<template>
  <div class="min-h-screen bg-background px-6 py-4 space-y-12">
    <div class="pt-5">
      <InputGroup>
        <InputGroupInput placeholder="Search..." v-model="searchInput" />
        <InputGroupAddon>
          <SearchIcon />
        </InputGroupAddon>
        <InputGroupAddon v-if="searchInput.length > 0" align="inline-end">
          {{ people.filter(c => containSearch(c.first_name ?? '')).length }}
          results
        </InputGroupAddon>
      </InputGroup>
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
        <Avatar>
          <AvatarImage src="https://github.com/shadcn.png" alt="@shadcn" />
          <AvatarFallback>CN</AvatarFallback>
        </Avatar>
        {{ content.first_name }}
      </div>
    </div>

    <div class="divider"></div>

    <Empty v-if="!people.length" text="You don't have a match yet" />

    <div v-else class="flex flex-col gap-2 overflow-y-auto h-[90%]">
      <ItemGroup>
        <template v-for="(person, index) in people" :key="person.username">
          <Item
            @click="convClick(person.username)"
            class="group relative flex items-center gap-3 transition-all duration-200 ease-out hover:bg-muted/50 active:bg-muted/80 cursor-pointer rounded-lg pl-4 pr-3 py-2.5 overflow-hidden before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-0 before:w-[3px] before:rounded-r-full before:bg-primary before:transition-all before:duration-300 before:ease-out hover:before:h-4/5"
          >
            <ItemMedia>
              <Avatar>
                <AvatarImage
                  :src="person.avatar ?? 'https://github.com/shadcn.png'"
                  class="grayscale"
                />
                <AvatarFallback>{{ person.username.charAt(0) }}</AvatarFallback>
              </Avatar>
            </ItemMedia>
            <ItemContent class="gap-1">
              <ItemTitle>{{ person.first_name }} {{ person.last_name }}</ItemTitle>
              <ItemDescription>{{ person.last_message?.content }}</ItemDescription>
            </ItemContent>
          </Item>
          <ItemSeparator class="my-0!" v-if="index !== people.length - 1" />
        </template>
      </ItemGroup>
    </div>
  </div>
</template>

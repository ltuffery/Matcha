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
import PeopleList from '@/components/chat/PeopleList.vue'
import Conversation from '@/components/chat/Conversation.vue'

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
const selectedUser = ref<User>({
  username: 'test',
  avatar: 'https://github.com/shadcn.png',
  first_name: 'Leo',
  last_name: 'Tuffery',
})

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
</script>

<template>
  <div class="h-screen bg-background py-4 flex gap-12 overflow-hidden">
    <div class="hidden lg:block shrink-0 max-w-sm">
      <PeopleList @change="console.log" :matches="matches" :people="people" />
    </div>
    <div class="w-full min-w-0 min-h-0 flex flex-col">
      <Conversation
        :user="selectedUser"
        class="min-w-0 overflow-x-hidden flex min-h-0"
      />
    </div>
  </div>
</template>

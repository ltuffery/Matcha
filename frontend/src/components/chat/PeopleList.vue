<script setup lang="ts">
import { SearchIcon } from 'lucide-vue-next'
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
import { computed, onMounted, ref, watch } from 'vue'
import router from '@/router'
import { UserGroupIcon } from '@lucide/vue'
import {
  Empty,
  EmptyDescription,
  EmptyHeader,
  EmptyMedia,
  EmptyTitle,
} from '@/components/ui/empty'
import { Separator } from '@/components/ui/separator'
import { useRoute } from 'vue-router'
import { ScrollArea } from '@/components/ui/scroll-area'
import { useTypingStore } from '@/store/isTyping'

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

const props = defineProps<{
  matches: ChatUser[]
  people: ChatUser[]
}>()

const emit = defineEmits<{
  (e: 'change', value: string): void
}>()

const route = useRoute()

const typingStore = useTypingStore();

const selectedUser = ref<string>('')
const search = ref<string>('')
const filteredPeople = computed(() => {
  const query = search.value.trim().toLowerCase()

  return props.people.filter(person =>
    person.first_name?.toLowerCase().includes(query),
  )
})

function convClick(username: string) {
  router.push({ name: 'messages.user', params: { username } })
}

onMounted(() => {
  selectedUser.value = route.params.username as string

  watch(
    () => route.params.username,
    newUsername => {
      selectedUser.value = newUsername as string
    },
  )
})
</script>

<template>
  <div class="h-full flex flex-col gap-2">
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

  <div v-if="matches.length" class="flex flex-col gap-2 mt-4">
    <div class="flex gap-4 overflow-x-auto items-center">
      <div
        v-for="(content, index) in matches"
        :key="index"
        @click="convClick(content.username)"
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

    <Empty v-if="!people.length" class="flex">
      <EmptyHeader>
        <EmptyMedia variant="icon">
          <UserGroupIcon />
        </EmptyMedia>
        <EmptyTitle>No one here</EmptyTitle>
        <EmptyDescription v-if="!people.length && !matches.length">
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
              @click="convClick(person.username)"
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
                    class="grayscale"
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
                <ItemDescription v-if="typingStore.isTyping(person.username)">
                  {{person.username}} is typing...</ItemDescription>
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

<script setup lang="ts">
import { Api } from '@/utils/api'
import { ref, watch } from 'vue'
import type { User } from '@/types'

import { Input } from '@/components/ui/input'
import { Card, CardContent } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import { Search, X } from 'lucide-vue-next'
import UserItem from '@/components/users/UserItem.vue'

const SEARCH_MIN_LENGTH = 3

const search = ref('')
const users = ref<User[]>([])
const loading = ref(false)
const hasSearched = ref(false)

let debounceTimer: ReturnType<typeof setTimeout>

const fetchUsers = async (query: string) => {
  loading.value = true

  console.log(query)

  const res = await Api.get(`search/users?q=${query}`).send()

  users.value = await res.json()
  loading.value = false
}

watch(
  () => search.value,
  () => {
    clearTimeout(debounceTimer)
    hasSearched.value = true

    if (search.value.length >= SEARCH_MIN_LENGTH) {
      debounceTimer = setTimeout(() => fetchUsers(search.value), 500)
    } else {
      users.value = []
      loading.value = false
      hasSearched.value = search.value.length > 0
    }
  },
)

const clearSearch = () => {
  search.value = ''
  users.value = []
  hasSearched.value = false
}
</script>

<template>
  <div class="max-w-3xl h-full m-auto pt-8 px-4">
    <div class="relative">
      <Search
        class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground"
      />
      <Input
        v-model="search"
        type="text"
        placeholder="Search for a user..."
        class="pl-9 pr-9 h-11"
      />
      <button
        v-if="search"
        @click="clearSearch"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
      >
        <X class="h-4 w-4" />
      </button>
    </div>

    <div class="mt-4 flex flex-col gap-3">
      <!-- Loading -->
      <template v-if="loading">
        <Card v-for="i in 3" :key="i">
          <CardContent class="flex items-center gap-4 p-4">
            <Skeleton class="h-12 w-12 rounded-full" />
            <div class="flex flex-col gap-2 flex-1">
              <Skeleton class="h-4 w-1/3" />
              <Skeleton class="h-3 w-1/2" />
            </div>
          </CardContent>
        </Card>
      </template>

      <template v-else-if="users.length">
        <UserItem
          v-for="user in users"
          :key="user.username"
          :profile="user"
          description=""
        />
      </template>

      <div
        v-else-if="hasSearched && search.length >= SEARCH_MIN_LENGTH"
        class="text-center text-muted-foreground py-10"
      >
        No users found for "{{ search }}"
      </div>

      <div v-else-if="!search" class="text-center text-muted-foreground py-10">
        Start typing to search for a user
      </div>
    </div>
  </div>
</template>

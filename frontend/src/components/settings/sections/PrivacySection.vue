<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Flag, MoreHorizontal } from 'lucide-vue-next'
import { ref } from 'vue'
import { useUsers } from '@/composable/useUsers'

interface BlockedUser {
  username: string
  avatar?: string
}

const users = useUsers()
users.fetchBlockedList()

const unblock = (username: string) => {
  users.unblock(username)
}

const report = (username: string) => {
  console.log('report', username)
}
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Blocked Profiles</CardTitle>
      <CardDescription>
        Manage the list of people you've blocked.
      </CardDescription>
    </CardHeader>
    <CardContent>
      <p v-if="!users.blockedUsers.value.length" class="w-full text-center text-sm text-muted-foreground">
        No profiles have been blocked so far.
      </p>

      <ul v-else class="divide-y divide-border">
        <li
          v-for="user in users.blockedUsers.value"
          :key="user.username"
          class="flex items-center gap-3 py-3 first:pt-0 last:pb-0"
        >
          <Avatar class="size-10">
            <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.username" />
            <AvatarFallback class="uppercase">
              {{ user.username.slice(0, 2) }}
            </AvatarFallback>
          </Avatar>

          <span class="flex-1 truncate font-medium">{{ user.username }}</span>

          <Button variant="destructive" size="sm" @click="unblock(user.username)">
            Unblock
          </Button>

          <!-- <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="ghost" size="icon" class="size-8">
                <MoreHorizontal class="size-4" />
                <span class="sr-only">More actions</span>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem class="text-destructive" @click="report(user.username)">
                <Flag class="mr-2 size-4" />
                Report
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu> -->
        </li>
      </ul>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { MoreVerticalIcon } from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { useUsers } from '@/composable/useUsers';
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps < {
  username?: string
}>()

const users = useUsers()
const router = useRouter()

const blockUser = async () => {
  if (!props.username) return

  try {
    await users.block(props.username)

    await router.push({ name: 'messages' })
  } catch (error) {
    console.error('user blocking error :', error)
  }
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" size="icon" aria-label="More options">
        <MoreVerticalIcon class="size-5" />
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="w-56" align="start">
      <DropdownMenuGroup>
        <DropdownMenuItem> Report </DropdownMenuItem>
        <DropdownMenuItem @select="blockUser"> Block </DropdownMenuItem>
      </DropdownMenuGroup>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

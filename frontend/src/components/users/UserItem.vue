<script setup lang="ts">
import { ArrowRightIcon } from '@lucide/vue'
import {
  Item,
  ItemActions,
  ItemContent,
  ItemDescription,
  ItemMedia,
  ItemTitle,
} from '@/components/ui/item'
import { Button } from '@/components/ui/button'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import type { User } from '@/types'
import { onMounted, ref } from 'vue'
import router from '@/router'

const props = defineProps<{
  profile: User
  description: string
}>()

const displayName = ref('')

const getInitials = (name: string) =>
  name
    .split(' ')
    .map(n => n[0])
    .join('')
    .slice(0, 2)
    .toUpperCase()

const toProfile = () => {
  router.push({
    name: 'profile',
    params: {
      username: props.profile.username,
    },
  })
}

onMounted(() => {
  displayName.value = props.profile.first_name + ' ' + props.profile.last_name
})
</script>

<template>
  <Item variant="outline" class="w-full">
    <ItemMedia>
      <Avatar class="size-10">
        <AvatarImage :src="profile.avatar ?? ''" />
        <AvatarFallback>{{ getInitials(displayName) }}</AvatarFallback>
      </Avatar>
    </ItemMedia>
    <ItemContent>
      <ItemTitle>{{ displayName }}</ItemTitle>
      <ItemDescription>{{ description }}</ItemDescription>
    </ItemContent>
    <ItemActions>
      <Button
        size="icon-sm"
        variant="outline"
        class="rounded-full"
        aria-label="Invite"
        @click="toProfile()"
      >
        <ArrowRightIcon />
      </Button>
    </ItemActions>
  </Item>
</template>

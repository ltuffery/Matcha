<script setup>
import { Card, CardContent } from '@/components/ui/card/index.ts'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar/index.ts'
import { Badge } from '@/components/ui/badge/index.ts'
import { Button } from '@/components/ui/button/index.ts'
import { Heart, Eye, Sparkles, Plus } from 'lucide-vue-next'
import {
  Item,
  ItemActions,
  ItemContent,
  ItemDescription,
  ItemMedia,
  ItemTitle,
} from '@/components/ui/item/index.ts'

defineProps({
  profile: {
    type: Object,
    required: true,
  },
  type: {
    type: String, // 'viewed' | 'liked'
    required: true,
  },
  dateLabel: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <Item variant="outline">
    <ItemMedia>
      <Avatar class="size-10">
        <AvatarImage :src="profile.avatar" />
        <AvatarFallback>ER</AvatarFallback>
      </Avatar>
    </ItemMedia>
    <ItemContent>
      <ItemTitle>{{ profile.name }}</ItemTitle>
      <ItemDescription>{{ dateLabel }}</ItemDescription>
    </ItemContent>
    <ItemActions>
      <Button
        size="icon-sm"
        variant="outline"
        class="rounded-full"
        aria-label="Invite"
      >
        <Plus />
      </Button>
    </ItemActions>
  </Item>
</template>

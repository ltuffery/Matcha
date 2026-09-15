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
        <DropdownMenuItem> Block </DropdownMenuItem>
      </DropdownMenuGroup>
    </DropdownMenuContent>
  </DropdownMenu>
</template>

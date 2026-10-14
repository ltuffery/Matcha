<script setup lang="ts">
import router from '@/router'
import { Button } from '@/components/ui/button'
import {
  Empty,
  EmptyContent,
  EmptyDescription,
  EmptyHeader,
  EmptyTitle,
} from '@/components/ui/empty'
</script>

<template>
  <Empty>
    <EmptyHeader>
      <EmptyTitle>404 - Not Found</EmptyTitle>
      <EmptyDescription>
        The page you're looking for doesn't exist.
      </EmptyDescription>
    </EmptyHeader>
    <EmptyContent>
      <EmptyDescription>
        <Button @click="() => router.push({ name: 'home' })">Back to home</Button>
      </EmptyDescription>
    </EmptyContent>
  </Empty>
</template>

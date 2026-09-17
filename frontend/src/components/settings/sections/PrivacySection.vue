<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { ref } from 'vue'

const blockedList = ref([])
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
      <p v-if="!blockedList.length" class="text-sm text-muted-foreground">
        No profiles have been blocked so far.
      </p>
    </CardContent>
  </Card>
</template>

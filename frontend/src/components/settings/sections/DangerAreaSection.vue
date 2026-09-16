<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Trash2 } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
</script>

<template>
  <Card class="border-destructive/50">
    <CardHeader>
      <CardTitle class="text-destructive">Danger zone</CardTitle>
      <CardDescription>
        These actions cannot be undone; please proceed with caution.
      </CardDescription>
    </CardHeader>
    <CardContent class="space-y-4">
      <div
        class="flex items-center justify-between rounded-lg border border-destructive/50 p-4"
      >
        <div>
          <p class="font-medium text-sm">Delete my Account</p>
          <p class="text-sm text-muted-foreground">
            All of your data will be permanently deleted.
          </p>
        </div>
        <Button variant="destructive">
          <Trash2 class="h-4 w-4 mr-2" />
          Delete
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

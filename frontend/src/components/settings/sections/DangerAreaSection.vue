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
import DangerDeleteDialog from '@/components/settings/sections/danger_area/DangerDeleteDialog.vue'
import { ref } from 'vue'

const open = ref<boolean>(false)
</script>

<template>
  <DangerDeleteDialog v-model:open="open" />

  <Card class="border-destructive/50">
    <CardHeader>
      <CardTitle class="text-destructive">Danger zone</CardTitle>
      <CardDescription>
        These actions cannot be undone; please proceed with caution.
      </CardDescription>
    </CardHeader>
    <CardContent class="space-y-4">
      <div
        class="flex items-center justify-between rounded-lg border border-destructive/50 p-4 flex-col gap-4 md:flex-row md:gap-0"
      >
        <div>
          <p class="font-medium text-sm">Delete my Account</p>
          <p class="text-sm text-muted-foreground mt-1">
            All of your data will be permanently deleted.
          </p>
        </div>
        <Button variant="destructive" class="w-full md:w-auto" @click="open = true">
          <Trash2 class="h-4 w-4 mr-2" />
          Delete
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

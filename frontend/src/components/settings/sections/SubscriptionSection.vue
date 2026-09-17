<script setup lang="ts">
import { Badge } from '@/components/ui/badge'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle class="flex items-center gap-2">
        Current Subscription
        <Badge>Premium</Badge>
      </CardTitle>
      <CardDescription>Renewal on October 15, 2026.</CardDescription>
    </CardHeader>
    <CardContent class="flex items-center justify-between">
      <div>
        <p class="text-2xl font-bold">
          19,99 €
          <span class="text-sm font-normal text-muted-foreground">/ mois</span>
        </p>
      </div>
      <div class="flex gap-2">
        <Button variant="outline">Changer your plan</Button>
        <Button variant="ghost" class="text-destructive hover:text-destructive">
          Cancel
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

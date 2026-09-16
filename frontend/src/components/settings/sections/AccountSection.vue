<script setup lang="ts">
import { Label } from '@/components/ui/label'
import { Input } from '@/components/ui/input'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Credentials</CardTitle>
      <CardDescription> Manage your email and password. </CardDescription>
    </CardHeader>
    <CardContent class="space-y-4">
      <div class="space-y-2">
        <Label>Email</Label>
        <Input type="email" placeholder="your@email.com" />
      </div>
      <Separator />
      <div class="space-y-2">
        <Label>New password</Label>
        <Input type="password" placeholder="********" />
      </div>
      <div class="flex justify-end">
        <Button>Save change</Button>
      </div>
    </CardContent>
  </Card>
</template>

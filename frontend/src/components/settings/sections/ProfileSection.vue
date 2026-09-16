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
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/components/ui/number-field'
import { Camera } from 'lucide-vue-next'
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Profile photos</CardTitle>
      <CardDescription>
        Add up to 5 photos. The first one is your main photo.
      </CardDescription>
    </CardHeader>
    <CardContent>
      <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
        <div
          v-for="i in 5"
          :key="i"
          class="aspect-square rounded-xl border-2 border-dashed border-muted-foreground/25 flex items-center justify-center hover:border-primary/50 hover:bg-muted/50 cursor-pointer transition-colors"
        >
          <Camera class="h-6 w-6 text-muted-foreground" />
        </div>
      </div>
    </CardContent>
  </Card>

  <Card>
    <CardHeader>
      <CardTitle>Personal Information</CardTitle>
      <CardDescription>
        This information is visible on your public profile.
      </CardDescription>
    </CardHeader>
    <CardContent class="space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-2">
          <Label>First name</Label>
          <Input placeholder="Camille" />
        </div>
        <div class="space-y-2">
          <Label>Last name</Label>
          <Input placeholder="Dupond" />
        </div>
        <div class="space-y-2">
          <NumberField id="age" :default-value="18" :min="18">
            <Label for="age">Age</Label>
            <NumberFieldContent>
              <NumberFieldDecrement />
              <NumberFieldInput />
              <NumberFieldIncrement />
            </NumberFieldContent>
          </NumberField>
        </div>
      </div>
      <div class="space-y-2">
        <Label>Bio</Label>
        <textarea
          class="flex min-h-24 w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
          placeholder="Tell us a little about yourself..."
        />
        <p class="text-xs text-muted-foreground text-right">0 / 500</p>
      </div>
      <div class="flex justify-end">
        <Button>Save changes</Button>
      </div>
    </CardContent>
  </Card>
</template>

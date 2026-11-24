<template>
  <main
    class="grid h-dvh grid-cols-1 place-content-center place-items-center bg-muted px-2"
  >
    <Toaster position="top-right" rich-colors />

    <Card class="w-full max-w-sm shadow-2xl">
      <!-- Chargement -->
      <template v-if="skeleton">
        <CardHeader class="gap-3">
          <Skeleton class="h-6 w-48" />
          <Skeleton class="h-4 w-64" />
        </CardHeader>
        <CardContent class="space-y-4">
          <Skeleton class="h-9 w-full" />
          <Skeleton class="h-9 w-full" />
        </CardContent>
      </template>

      <!-- Formulaire -->
      <form
        v-else-if="pageState === 'goodToken'"
        @submit.prevent="changePassword"
      >
        <CardHeader>
          <CardTitle class="text-2xl">Create a new password</CardTitle>
          <CardDescription>
            Choose a new password for your account.
          </CardDescription>
        </CardHeader>

        <CardContent class="space-y-4">
          <div class="space-y-2">
            <Label for="new-password">New password</Label>
            <div class="relative">
              <KeyRound
                class="absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
              />
              <Input
                id="new-password"
                v-model="newPassword"
                type="password"
                placeholder="********"
                autocomplete="new-password"
                class="pl-9"
                required
              />
            </div>
          </div>

          <div class="space-y-2">
            <Label for="confirm-password">Confirm new password</Label>
            <div class="relative">
              <KeyRound
                class="absolute left-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
              />
              <Input
                id="confirm-password"
                v-model="confirmPassword"
                type="password"
                placeholder="********"
                autocomplete="new-password"
                class="pl-9"
                required
              />
            </div>
          </div>
        </CardContent>

        <CardFooter class="justify-end">
          <Button type="submit" :disabled="submitting">
            <Loader2 v-if="submitting" class="size-4 animate-spin" />
            Send
          </Button>
        </CardFooter>
      </form>

      <!-- Token invalide / expiré -->
      <CardHeader v-else-if="pageState === 'badToken'" class="items-center text-center">
        <div
          class="mb-2 flex size-12 items-center justify-center rounded-full bg-destructive/10 text-destructive"
        >
          <XCircle class="size-6" />
        </div>
        <CardTitle>Link invalid</CardTitle>
        <CardDescription>
          You don't have right to change this password or your link has
          expired.
        </CardDescription>
      </CardHeader>

      <!-- Succès -->
      <CardHeader v-else class="items-center text-center">
        <div
          class="mb-2 flex size-12 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-950"
        >
          <CheckCircle2 class="size-6" />
        </div>
        <CardTitle>Password changed</CardTitle>
        <CardDescription>
          Your password has been successfully changed!
        </CardDescription>
      </CardHeader>
    </Card>
  </main>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { CheckCircle2, KeyRound, Loader2, XCircle } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import { ApiClient } from '@/api/client'
import { Button } from '@/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Skeleton } from '@/components/ui/skeleton'
import { Toaster } from '@/components/ui/sonner'
import { ApiError } from '@/api/errors'

const pageState = ref<'badToken' | 'goodToken' | 'updatePwdSuccess'>('badToken')
const skeleton = ref(true)
const submitting = ref(false)
const tokenPwd = ref<string>()

const newPassword = ref('')
const confirmPassword = ref('')

const urlParams = new URLSearchParams(window.location.search)

async function changePassword() {
  if (newPassword.value !== confirmPassword.value) {
    toast.error('The passwords need to be the same')
    return
  }

  submitting.value = true
  try {
    const req = await ApiClient.post('forgot/change-password', {
      username: urlParams.get('user'),
      newPassword: newPassword.value,
      confirmPassword: confirmPassword.value,
      token: tokenPwd.value,
    })
    const data = (await req.json()) as { success: boolean; error?: string }

    if (data.success) {
      toast.success('The password is changed')
      pageState.value = 'updatePwdSuccess'
    } else {
      toast.error(data.error ?? 'Unknown error')
    }
  } catch (err) {
    if (err instanceof ApiError || err instanceof Error) {
      toast.error(err.message)
    } else {
      toast.error('Unknown error')
    }
  } finally {
    submitting.value = false
    newPassword.value = ''
    confirmPassword.value = ''
  }
}

const checkToken = async () => {
  try {
    const req = await ApiClient.post('forgot/token-verify', {
      username: urlParams.get('user'),
      token: urlParams.get('token'),
    })
    const data = (await req.json()) as { success: boolean; token?: string }
    if (data.success) {
      pageState.value = 'goodToken'
      tokenPwd.value = data.token
    } else {
      pageState.value = 'badToken'
    }
  } catch {
    pageState.value = 'badToken'
  } finally {
    skeleton.value = false
  }
}
checkToken()
</script>

<template>
  <main
    class="grid h-dvh grid-cols-1 place-content-center place-items-center bg-muted px-2"
  >
    <Card class="w-full max-w-sm shadow-2xl">
      <!-- Skeleton pendant le chargement -->
      <template v-if="loading">
        <CardHeader class="items-center gap-3">
          <Skeleton class="size-12 rounded-full" />
          <Skeleton class="h-5 w-40" />
          <Skeleton class="h-4 w-56" />
        </CardHeader>
      </template>

      <!-- Résultat -->
      <template v-else>
        <CardHeader class="items-center text-center">
          <div
            class="mb-2 flex size-12 items-center justify-center rounded-full"
            :class="
              responseOK
                ? 'bg-green-100 text-green-600 dark:bg-green-950'
                : 'bg-destructive/10 text-destructive'
            "
          >
            <CheckCircle2 v-if="responseOK" class="size-6" />
            <XCircle v-else class="size-6" />
          </div>
          <CardTitle>
            {{ responseOK ? 'Email verified' : 'Verification failed' }}
          </CardTitle>
          <CardDescription>
            <template v-if="responseOK">
              Your email has been verified.
            </template>
            <template v-else>
              Bad email verify link, or email already verified.
            </template>
          </CardDescription>
        </CardHeader>
      </template>
    </Card>
  </main>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { CheckCircle2, XCircle } from 'lucide-vue-next'
import { ApiClient } from '@/api/client'
import {
  Card,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'

const responseOK = ref(false)
const loading = ref(true)

const checkToken = async () => {
  const urlParams = new URLSearchParams(window.location.search)

  const info = {
    username: urlParams.get('user'),
    token: urlParams.get('token'),
  }

  try {
    const req = await ApiClient.post('email/token', info)
    const data = (await req.json()) as { success: boolean }
    responseOK.value = data.success
  } catch {
    responseOK.value = false
  } finally {
    loading.value = false
  }
}

checkToken()
</script>

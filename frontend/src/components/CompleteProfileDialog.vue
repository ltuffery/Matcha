<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/store/useAuthStore'
import {
  AlertDialog,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'
import MapLocator, {
  type Location,
} from '@/components/settings/sections/preferences/MapLocator.vue'

const auth = useAuthStore()
const localisation = ref<Location | null>(null)
const submitting = ref(false)
const errorMessage = ref<string | null>(null)

async function submit(): Promise<void> {
  submitting.value = true
  errorMessage.value = null
  try {
    if (localisation.value != null) {
      await auth.updateLocalisation(
        localisation.value.lng,
        localisation.value.lat,
      )
      await auth.fetchMe()
    }
  } catch (err: unknown) {
    errorMessage.value =
      err instanceof Error ? err.message : 'Unable to save the address.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <AlertDialog :open="auth.profileIncomplete">
    <AlertDialogContent @escape-key-down.prevent>
      <AlertDialogHeader>
        <AlertDialogTitle>Complete your profile</AlertDialogTitle>
        <AlertDialogDescription>
          Please provide your address to continue using the application.
        </AlertDialogDescription>
      </AlertDialogHeader>

      <form class="space-y-3" @submit.prevent="submit">
        <MapLocator v-model="localisation" />
        <p v-if="errorMessage" class="text-sm text-destructive">
          {{ errorMessage }}
        </p>
        <AlertDialogFooter>
          <Button type="submit" :disabled="submitting || !localisation"
            >Save</Button
          >
        </AlertDialogFooter>
      </form>
    </AlertDialogContent>
  </AlertDialog>
</template>

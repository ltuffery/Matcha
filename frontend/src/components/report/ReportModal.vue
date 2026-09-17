<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { FlagIcon, Loader2Icon } from 'lucide-vue-next'

const props = defineProps<{
  userId: string
}>()

const REPORT_REASONS = [
  { value: 'fake_account', label: 'Fake account' },
  { value: 'inappropriate_content', label: 'Inappropriate content' },
  { value: 'harassment', label: 'Harassment' },
  { value: 'other', label: 'Other' },
] as const

const isOpen = ref(false)
const reason = ref<string>()
const isSubmitting = ref(false)
const error = ref<string>()

async function sendReport() {
  if (!reason.value) {
    error.value = 'Please select a reason.'
    return
  }

  error.value = undefined
  isSubmitting.value = true

  try {
    // await api.reportUser(props.userId, reason.value)
    isOpen.value = false
    reason.value = undefined
  } catch (e) {
    error.value = 'Something went wrong. Please try again.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <Button
    class="size-12 rounded-full border border-white/20 bg-white/10 text-white shadow-lg backdrop-blur-xl transition-all duration-200 hover:scale-110 hover:bg-white/20 active:scale-90"
    size="icon"
    variant="ghost"
    @click="isOpen = true"
  >
    <FlagIcon class="size-6" />
  </Button>

  <Dialog v-model:open="isOpen">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Report user</DialogTitle>
        <DialogDescription>
          Why do you want to report this user?
        </DialogDescription>
      </DialogHeader>

      <Select v-model="reason">
        <SelectTrigger class="w-full">
          <SelectValue placeholder="Select a reason" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem
            v-for="item in REPORT_REASONS"
            :key="item.value"
            :value="item.value"
          >
            {{ item.label }}
          </SelectItem>
        </SelectContent>
      </Select>

      <p v-if="error" class="text-sm text-destructive">
        {{ error }}
      </p>

      <DialogFooter>
        <Button
          variant="outline"
          :disabled="isSubmitting"
          @click="isOpen = false"
        >
          Cancel
        </Button>
        <Button :disabled="isSubmitting" @click="sendReport">
          <Loader2Icon v-if="isSubmitting" class="mr-2 size-4 animate-spin" />
          Send
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

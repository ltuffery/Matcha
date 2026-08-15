<script setup lang="ts">
import { ref, watch } from 'vue'

interface ToastData {
  type: string
  message: string
  data?: {
    title?: string
    timeout?: number
    action?: () => void
    typebtn?: number
  }
}

const toasts = ref<ToastData[]>([])
const stack = ref<ToastData[]>([])

const props = defineProps<{
  maxToasts?: number
  posX?: string
  posY?: string
}>()

function selfDestroy(index: number) {
  toasts.value.splice(index, 1)
}

function setTimeOutItem(toast: ToastData, timeout = 3000) {
  setTimeout(() => {
    const index = toasts.value.indexOf(toast)
    if (index !== -1) {
      toasts.value.splice(index, 1)
    }
  }, timeout)
}

watch(
  toasts,
  newToasts => {
    if (newToasts.length < (props.maxToasts ?? 3) && stack.value.length > 0) {
      const nextToast = stack.value.shift()
      if (nextToast) {
        toasts.value.push(nextToast)
        setTimeOutItem(nextToast, nextToast.data?.timeout || 3000)
      }
    }
  },
  { deep: true },
)

function createToast(content: string, type: string, data?: ToastData['data']) {
  const toast: ToastData = { type, message: content, data }

  if (toasts.value.length < (props.maxToasts ?? 3)) {
    toasts.value.push(toast)
    setTimeOutItem(toast, data?.timeout || 3000)
  } else {
    stack.value.push(toast)
  }
}

function addError(content: string, data: ToastData['data'] = undefined) {
  createToast(content, 'error', data)
}

function addSuccess(content: string, data: ToastData['data'] = undefined) {
  createToast(content, 'success', data)
}

function addInfo(content: string, data: ToastData['data'] = undefined) {
  createToast(content, 'info', data)
}

function addWarning(content: string, data: ToastData['data'] = undefined) {
  createToast(content, 'warning', data)
}

defineExpose({
  addError,
  addSuccess,
  addInfo,
  addWarning,
})
</script>

<template>
  <div
    :class="[
      'toast',
      `toast-${props.posY ?? 'top'}`,
      `toast-${props.posX ?? 'center'}`,
      'overflow-y-auto',
    ]"
  >
    <div
      v-for="(toast, index) in toasts"
      :key="index"
      :class="['alert', `alert-${toast.type}`, 'flex']"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        class="h-6 w-6 shrink-0 stroke-current"
      >
        <path
          v-if="toast.type === 'info'"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        ></path>

        <path
          v-else-if="toast.type === 'success'"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
        />

        <path
          v-else-if="toast.type === 'error'"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
        />

        <path
          v-else-if="toast.type === 'warning'"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
        />
      </svg>
      <div v-if="toast.data && toast.data.title">
        <h3 class="font-bold">{{ toast.data.title }}</h3>
        <div class="text-xs">{{ toast.message }}</div>
      </div>
      <span v-else class="text-wrap">{{ toast.message }}</span>
      <div
        v-if="toast.data && toast.data.action"
        class="flex w-full justify-end"
      >
        <button
          v-if="!toast.data.typebtn || toast.data.typebtn == 1"
          @click="toast.data.action"
          class="btn btn-sm"
        >
          See
        </button>
        <button
          v-if="toast.data.typebtn == 2"
          @click="selfDestroy(index)"
          class="btn btn-sm mr-1"
        >
          Deny
        </button>
        <button
          v-if="toast.data.typebtn == 2"
          @click="toast.data.action"
          class="btn btn-sm btn-primary"
        >
          Accept
        </button>
      </div>
    </div>
  </div>
</template>

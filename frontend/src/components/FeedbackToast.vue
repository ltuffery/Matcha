<script setup>
import { ref } from 'vue'

const toasts = ref([])

function selfDestroy(index)
{
    toasts.value.splice(index, 1)
}

function addError(content, action = null, type = 1) {
    const toast = { type: 'error', message: content, action: action, typebtn: type }
    toasts.value.push(toast)

    setTimeout(() => {
        const index = toasts.value.indexOf(toast)
        if (index !== -1) {
            toasts.value.splice(index, 1)
        }
    }, 3000)
}

function addSuccess(content, action = null, type = 1) {
    const toast = { type: 'success', message: content, action: action, typebtn: type }
    toasts.value.push(toast)

    setTimeout(() => {
        const index = toasts.value.indexOf(toast)
        if (index !== -1) {
            toasts.value.splice(index, 1)
        }
    }, 3000)
}

function addInfo(content, action = null, type = 1) {
    const toast = { type: 'info', message: content, action: action, typebtn: type }
    toasts.value.push(toast)

    setTimeout(() => {
        const index = toasts.value.indexOf(toast)
        if (index !== -1) {
            toasts.value.splice(index, 1)
        }
    }, 3000)
}

function addWarning(content, action = null, type = 1) {
    const toast = { type: 'warning', message: content, action: action, typebtn: type }
    toasts.value.push(toast)

    setTimeout(() => {
        const index = toasts.value.indexOf(toast)
        if (index !== -1) {
            toasts.value.splice(index, 1)
        }
    }, 3000)
}

defineExpose({
    addError,
    addSuccess,
    addInfo,
    addWarning
})
</script>

<template>
    <div class="toast toast-top toast-end overflow-y-auto max-h-[25%]">
        <!-- Parcourir les toasts dynamiques -->
        <div
            v-for="(toast, index) in toasts"
                :key="index"
                :class="['alert', `alert-${toast.type}`, 'flex']">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                class="h-6 w-6 shrink-0 stroke-current">

                <path
                    v-if="toast.type === 'info'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>

                <path
                    v-if="toast.type === 'success'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />

                <path
                    v-if="toast.type === 'error'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                <path
                    v-if="toast.type === 'warning'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="text-wrap">{{ toast.message }}</span>
            <div v-if="toast.action">
                <button 
                    v-if="toast.typebtn == 1" 
                    @click="toast.action"
                    class="btn btn-sm">
                    See
                </button>
                <button 
                    v-if="toast.typebtn == 2" 
                    @click="selfDestroy(index)"
                    class="btn btn-sm mr-1">
                    Deny
                </button>
                <button 
                    v-if="toast.typebtn == 2" 
                    @click="toast.action"
                    class="btn btn-sm btn-primary">
                    Accept
                </button>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import Avatar from '@/components/Avatar.vue'

const props = defineProps<{
  avatar?: string
  firstName: string
  username?: string | false
  lastMessage?: string
  label?: number | null
}>()
</script>

<template>
  <div class="flex rounded-lg cursor-pointer select-none hover:bg-muted">
    <Avatar type="squircle" :src="props.avatar" :username="props.username as string ?? ''" />

    <div class="grow flex flex-col gap-2 justify-center p-2 w-[80%]">
      <div class="flex justify-between w-full">
        <div class="truncate max-w-[60%] text-xl">
          {{ props.firstName }}
        </div>
        <div>
          <div v-if="props.label && props.label > 99" class="badge badge-primary">99+</div>
          <div v-else-if="props.label && props.label > 0" class="badge badge-primary">
            {{ props.label }}
          </div>
        </div>
      </div>
      <div class="truncate text-sm">
        {{ props.lastMessage }}
      </div>
    </div>
  </div>
  <div class="divider my-0 pl-[20%]"></div>
</template>

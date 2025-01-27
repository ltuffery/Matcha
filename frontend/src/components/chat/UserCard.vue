<script setup>
import Avatar from '@/components/Avatar.vue'

const props = defineProps({
  avatar: {
    type: String,
    default:
      'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp',
  },
  firstName: {
    type: String,
    required: true,
    default: 'Profile deleted',
  },
  username: {
    type: String,
    default: false,
  },
  lastMessage: {
    type: String,
    default: 'Send a message !',
  },
  label: {
    type: Number,
    default: null,
  },
})
</script>

<template>
  <div class="flex rounded-lg cursor-pointer select-none hover:bg-base-300">
    <Avatar type="squircle" :src="props.avatar" :username="props.username" />
    <!--    <div class="flex-none mask mask-squircle w-[20%]">-->
    <!--      <img class="object-cover rounded-lg" :src="props.avatar" />-->
    <!--    </div>-->

    <div class="grow flex flex-col gap-2 justify-center p-2 w-[80%]">
      <div class="flex justify-between w-full">
        <div class="truncate max-w-[60%] text-xl">
          {{ props.firstName }}
        </div>
        <div>
          <div v-if="props.label > 99" class="badge badge-primary">99+</div>
          <div v-else-if="props.label > 0" class="badge badge-primary">
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

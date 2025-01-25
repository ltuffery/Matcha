<script setup>
const props = defineProps({
  type: {
    type: String,
    default: '',
  },
  src: {
    type: String,
    require: true,
    default:
      'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp',
  },
  width: {
    type: String,
    default: '16',
  },
  addedCss: {
    type: String,
    default: '',
  },
  online: {
    type: Boolean,
    require: true,
  },
})
</script>

<template>
  <template v-if="props.type === 'squircle'">
    <div :class="['avatar', online ? 'online' : 'offline']">
      <div :class="[`mask mask-squircle w-${width}`, addedCss]">
        <img :src="src" alt="User avatar" />
      </div>
    </div>
  </template>

  <template v-else-if="props.type === 'r-full'">
    <div :class="['avatar', props.online ? 'online' : 'offline']">
      <div :class="[`rounded-full w-${width}`, addedCss]">
        <img :src="src" alt="User avatar" />
      </div>
    </div>
  </template>

  <template v-else>
    <div :class="['avatar', props.online ? 'online' : 'offline']">
      <div :class="[`w-${width}`, addedCss]">
        <img :src="src" alt="User avatar" />
      </div>
    </div>
  </template>
</template>

<script setup>
const props = defineProps({
  avatar: {
    type: String,
    default: 'https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp',
  },
  first_name: {
    type: String,
    required: true,
	default: "Profile deleted"
  },
  lastMessage: {
    type: String,
	default: "Send a message !"
  },
  label: {
    type: Number,
	default: null
  }
})
</script>

<template>
	<div class="flex rounded-lg cursor-pointer select-none hover:bg-base-300">
		<div class="flex-none mask mask-squircle w-[20%]">
			<img class="object-cover rounded-lg" :src="props.avatar" />
		</div>
		
		<div class="grow flex flex-col gap-2 justify-center p-2 w-[80%]">
		<div class="flex justify-between w-full">
			<div class="truncate max-w-[60%] text-xl">
				{{ props.first_name }}
			</div>
			<div>
				<div v-if="label == 1" class="badge badge-neutral">New Match</div>
				<div v-else-if="label == 2" class="badge badge-accent">Message</div>
			</div>
		</div>
		<div class="truncate text-sm">
			{{ props.lastMessage }}
		</div>
		</div>
	</div>
	<div class="divider my-0 pl-[20%]"></div>
</template>
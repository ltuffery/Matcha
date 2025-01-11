<script setup lang="ts">
import UserMatch from "@/components/chat/UserMatch.vue";
import Message from "@/components/chat/Message.vue";
import UserCard from "@/components/chat/UserCard.vue";
import { Api } from "@/utils/api";
import {ref} from "vue";
import router from '@/router'

const matches = ref([1,1,1,1,1,1,1,1,1,1,1,1,1,1])
let isGoodConv = ref(false)

Api.get('/users/me/matches').send()
	.then(res => {
		if (res.status === 401) {
			return [1,1,1,1,1,1,1,1,1,1,1]
		}

		return res.json()
	})
	.then(data => {
		matches.value = data
	})

function convClick(username)
{
	isGoodConv.value = true
	router.push({ name: `chat/${username}` })
}

</script>

<template>
	<div class="flex bg-base-300 h-dvh w-full justify-center items-center">
		<div class="bg-base-200 lg:h-[95%] h-full w-full max-w-3xl px-4">

		<!-- ########## List of users ########## -->

			<div v-if="!isGoodConv" class="flex flex-col h-full">

				<div class="pt-5">
					<label class="input input-bordered flex items-center gap-2">
						<input type="text" class="grow" placeholder="Search" />
						<svg
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 16 16"
							fill="currentColor"
							class="h-4 w-4 opacity-70">
							<path
								fill-rule="evenodd"
								d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
								clip-rule="evenodd" />
						</svg>
					</label>
				</div>


				<div class="divider"></div>

				<div class="flex flex-col gap-2 overflow-y-auto h-[90%]">
					<div
						v-for="(content, index) in matches"
						:key="index"
					>

						<UserCard @click="convClick('test')" :label="index%3" first_name="sssssssssssssssssssssssssssssssssssssssss" />
					


					</div>
				</div>
			</div>

		<!-- ########## Conversation view ########## -->

			<div v-else class="flex flex-col h-full">
				<!-- Head -->
				<div class="flex items-center justify-between pt-4">
					<div class="rounded-lg cursor-pointer">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 24 24"
							fill="none"
							class="h-12 w-12"
						>
						<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
						<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
						<g id="SVGRepo_iconCarrier">
							<path d="M15 19L9 12L10.5 10.25M15 5L13 7.33333" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</g>
						</svg>
					</div>

					<div class="text-3xl px-4 truncate">Name</div>
					<div></div>
				</div>

				<div class="divider"></div>

				<div class="h-full ">
					

					<Message message="coucou" is-me />
					<Message message="okey" />
					<Message message="okey" />
					<Message message="okey" />
					<Message message="okey" />


				</div>

				<!-- Input -->
				<div class="py-4">
					<textarea
						placeholder="Your message"
						class="textarea w-full p-4"
						style="resize:none"></textarea>
				</div>

			</div>
		




		</div>
	</div>
</template>

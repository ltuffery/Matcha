<script setup>
import UserCard from "@/components/chat/UserCard.vue";
import { Api } from "@/utils/api";
import {ref} from "vue";
import router from '@/router'

const matches = ref([])

Api.get('/users/me/matches').send()
	.then(res => {
		if (res.status === 401) {
			return [1,1,1,1,1,1,1,1,1,1,1]
		}

		return res.json()
	})
	.then(data => {
		data.sort((a,b) => b.lastMessage?.created_at - a.lastMessage?.created_at)
		matches.value = data
	})


// matches.value.sort((a,b) => b.lastMessage?.created_at - a.lastMessage?.created_at)
function convClick(username)
{
	router.push({ name: `conversation`, params: { username }})
}

</script>

<template>
	<div class="flex bg-base-300 h-dvh w-full justify-center items-center">
		<div class="bg-base-200 lg:h-[95%] h-full w-full max-w-3xl px-4">

		<!-- ########## List of users ########## -->

			<div class="flex flex-col h-full">

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

				<div v-if="!matches.length" class="flex flex-col gap-7 w-full h-full justify-center items-center">
					<span class="text-2xl">You don't have a match yet</span>
					<button @click="router.push({name:'main'})" class="btn btn-outline btn-primary">On your matchs</button>
				</div>

				<div v-else class="flex flex-col gap-2 overflow-y-auto h-[90%]">
					<div
						v-for="(content, index) in matches"
						:key="index"
						@click="convClick(content.username)"
					>

						<UserCard :label="index%3" :lastMessage="content.last_message?.content" :first_name="content.first_name" />
					


					</div>

				</div>
			</div>


		</div>
	</div>
</template>

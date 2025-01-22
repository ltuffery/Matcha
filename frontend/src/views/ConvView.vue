<script setup>
import Message from "@/components/chat/Message.vue";
import { Api } from "@/utils/api";
import {ref,onMounted} from "vue";
import router from '@/router'
import { useRoute } from 'vue-router'

const route = useRoute()

const messages = ref ([])

const newMessageContent = ref()

const temporaryMsg = ref([])

const scrollableDiv = ref()

async function getMatchs(){
	let response = await Api.get(`/users/me/matches/${route.params.username}`).send()
	messages.value = await response.json()
}


function sendMessage()
{
	temporaryMsg.value.push(newMessageContent.value)
	Api.post(`/users/me/matches/${route.params.username}`).send({content: newMessageContent.value})
	newMessageContent.value = ""
	scrollbarToEnd()
}

function scrollbarToEnd()
{
	if (scrollableDiv.value) {
		scrollableDiv.value.scrollTop = scrollableDiv.value.scrollHeight;
	  }
}

function test(value)
{
	Api.post(`/users/me/matches/${route.params.username}`).send({content: value.target.innerText})
	scrollbarToEnd()
}

function handleKeydown(event)
{
	if (event.key === "Enter" && !event.shiftKey) {
		event.preventDefault();
		sendMessage();
	}
}

onMounted(async () => {
	await getMatchs()
	scrollbarToEnd()
	});
</script>

<template>
	<div class="flex bg-base-300 h-dvh w-full justify-center items-center">
		<div class="bg-base-200 lg:h-[95%] h-full w-full max-w-3xl px-4">

		<!-- ########## Conversation view ########## -->

			<div class="flex flex-col h-full">
				<!-- Head -->
				<div class="flex items-center justify-between pt-4">
					<div @click="router.back()" class="rounded-lg cursor-pointer">
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

					<div class="text-3xl px-4 truncate">{{route.params.username}}</div>
					<div></div>
				</div>

				<div class="divider"></div>

				<div class="h-full overflow-auto relative" ref="scrollableDiv">
					
					<div v-for="(content, index) in messages"
						:key="index">
						<Message :message="content.content" :is-me="content.sender.username != route.params.username" />
					</div>

					<div v-for="(content, index) in temporaryMsg"
						:key="index">
						<Message :message="content" is-me tempo />
					</div>


					<div v-if="!messages.length" class="absolute bottom-0 left-0 w-full h-full flex flex-col justify-between">
						<div></div>
						<div class="flex flex-col items-center">
							<div><img src="../assets/img/mopper.png" ></div>
							<div class="text-2xl">No message yet</div>
						</div>
						<div class="flex gap-2 w-full overflow-x-auto">
							<div @click="test" class="badge badge-outline text-nowrap hover:bg-base-300 select-none cursor-pointer badge-lg">Hey salut toi</div>
							<div class="badge badge-outline text-nowrap hover:bg-base-300 select-none cursor-pointer badge-lg">Coucou</div>
							<div class="badge badge-outline text-nowrap hover:bg-base-300 select-none cursor-pointer badge-lg">hola</div>
						</div>
					</div>


				</div>

				<!-- Input -->
				<div class="flex items-center py-4">
					<textarea
						v-model="newMessageContent"
						@keydown="handleKeydown"
						placeholder="Your message"
						class="textarea rounded-r-[0] focus:outline-none border-none textarea-xs w-full p-4"
						style="resize:none"></textarea>
					
					<div class="flex items-center h-full rounded-r-badge bg-base-100 bg-opacity-60 cursor-pointer hover:bg-base-300 p-4" @click="sendMessage">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 24 24"
							fill="none"
							class="h-8 w-8"
						>
							<g id="SVGRepo_bgCarrier" stroke-width="0"></g>
							<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
							<g id="SVGRepo_iconCarrier">
								<path d="M20.6281 9.09373C21.8764 5.34874 22.5006 3.47624 21.5122 2.48782C20.5237 1.49939 18.6511 2.12356 14.906 3.37189L10.2404 4.92704M5.57477 6.48218C3.49295 7.1761 2.45203 7.52305 2.13608 8.28637C2.06182 8.46577 2.01692 8.65596 2.00311 8.84963C1.94433 9.67365 2.72018 10.4495 4.27188 12.0011L4.55451 12.2837C4.80921 12.5384 4.93655 12.6658 5.03282 12.8075C5.22269 13.0871 5.33046 13.4143 5.34393 13.7519C5.35076 13.9232 5.32403 14.1013 5.27057 14.4574C5.07488 15.7612 4.97703 16.4131 5.0923 16.9147C5.32205 17.9146 6.09599 18.6995 7.09257 18.9433C7.59255 19.0656 8.24576 18.977 9.5522 18.7997L9.62363 18.79C9.99191 18.74 10.1761 18.715 10.3529 18.7257C10.6738 18.745 10.9838 18.8496 11.251 19.0285C11.3981 19.1271 11.5295 19.2585 11.7923 19.5213L12.0436 19.7725C13.5539 21.2828 14.309 22.0379 15.1101 21.9985C15.3309 21.9877 15.5479 21.9365 15.7503 21.8474C16.4844 21.5244 16.8221 20.5113 17.4975 18.4851L19.0628 13.7894" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
								<path d="M6 18L9.75 14.25M21 3L12.5 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
							</g>
						</svg>
					</div>
				</div>

			</div>
		




		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'

const tags = ref([{"name":"velo", "selected":false},{"name":"je sais pas", "selected":false}, {"name":"test", "selected":false}, {"name":"Restoration", "selected":false}, {"name":"Lancée de poid", "selected":false}, {"name":"test", "selected":false}, {"name":"icule", "selected":false}])

function addTagSelected(tag)
{
	tag.selected = !tag.selected
}
</script>

<template>
	<div class="">
		<div class="w-full">
			<label>Tags Selected :</label>
			<div class="flex flex-wrap bg-base-200 p-2 mt-1 gap-1 w-full min-h-10 rounded-box">
				<div @click="addTagSelected(tag)" v-for="tag in tags.filter(tag => tag.selected)" :key="tag" class="badge badge-outline badge-lg gap-1 cursor-pointer bg-base-100 hover:bg-base-100 hover:badge-outline hover:badge-error">
					<svg
						xmlns="http://www.w3.org/2000/svg"
						fill="none"
						viewBox="0 0 24 24"
						class="inline-block h-4 w-4 stroke-current">
						<path
						stroke-linecap="round"
						stroke-linejoin="round"
						stroke-width="2"
						d="M6 18L18 6M6 6l12 12"></path>
					</svg>
					{{tag.name}}
				</div>
			</div>
		</div>
		<div class="flex flex-wrap gap-2 mt-3">
			<div @click="addTagSelected(tag)" v-for="tag in tags.filter(tag => !tag.selected)" :key="tag" class="badge badge-outline badge-lg hover:bg-base-200 cursor-pointer">
				{{tag.name}}
			</div>
		</div>
	</div>
</template>
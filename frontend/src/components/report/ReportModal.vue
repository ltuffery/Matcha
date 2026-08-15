<script setup lang="ts">
import { ref } from 'vue'
import { Api } from '@/utils/api'

const props = defineProps<{
  username: string
}>()

const raison = ref<string>()

const sendReport = () => {
  Api.post(`/users/${props.username}/report`).send({
    raison: raison.value,
  })
}
</script>

<template>
  <button onclick="report_modal.showModal()">
    <img
      src="@/assets/icons/flag.svg"
      alt="more option btn"
      class="size-10 my-2 cursor-pointer"
    />
  </button>
  <dialog id="report_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Report</h3>
      <p class="py-4">Why do you want to report this user ?</p>
      <select v-model="raison" class="select select-bordered w-full max-w-xs">
        <option disabled selected>Select raison</option>
        <option value="Fake Account">Fake Account</option>
        <option value="Why Not">Why Not</option>
      </select>
      <div class="modal-action">
        <form method="dialog">
          <button class="btn">Cancel</button>
        </form>
        <form method="dialog">
          <button @click="sendReport" class="btn btn-primary">Send</button>
        </form>
      </div>
    </div>
  </dialog>
</template>

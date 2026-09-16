<script setup lang="ts">
import { ref } from 'vue'
import { Api } from '@/utils/api'
import { FlagIcon } from '@lucide/vue'
import { Button } from '@/components/ui/button'

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
  <Button
    class="flex size-12 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white shadow-lg backdrop-blur-xl transition-all duration-200 hover:scale-110 hover:bg-white/20 active:scale-90"
    onclick="report_modal.showModal()"
  >
    <FlagIcon class="size-6" />
  </Button>
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

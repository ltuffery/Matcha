<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { computed, ref } from 'vue'
import { useAuthStore } from '@/store/useAuthStore'
import {useAuth} from "@/composable/useAuth";

const open = defineModel<boolean>('open', { required: true })

const me = useAuthStore()
const validate = ref('')
const disabled = computed(() => {
  return validate.value !== me.user?.username
})

const onDelete = async () => {
  await me.deleteAccount()
  await useAuth().disconnect()
  window.location.reload()
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogContent class="sm:max-w-106.25">
      <DialogHeader>
        <DialogTitle>Suppression de compte</DialogTitle>
        <DialogDescription>
          Êtes-vous sûr de vouloir supprimer ce compte ? Cette action est
          définitive ; aucun retour en arrière ne sera possible.
        </DialogDescription>
      </DialogHeader>
      <div class="grid gap-4">
        <div class="grid gap-3">
          <Label>Écris {{ me.user?.username }} pour confirmer :</Label>
          <Input v-model="validate" />
        </div>
      </div>
      <DialogFooter>
        <DialogClose as-child>
          <Button variant="outline"> Annuler </Button>
        </DialogClose>
        <Button variant="destructive" :disabled="disabled" @click="onDelete">
          Supprimer
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

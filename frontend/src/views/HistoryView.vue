<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { Api } from '@/utils/api'
import { likesStore } from '@/store/likes'
import type { User } from '@/types'

import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import { Heart, HeartOff } from 'lucide-vue-next'
import Empty from '@/components/core/Empty.vue'

interface HistoryData {
  views: User[]
  likes: User[]
}

const views = ref<User[]>()
const loading = ref(true)
const openDialogUsername = ref<string | null>(null)

onMounted(async () => {
  try {
    const res = await Api.get('/users/me/history').send()
    const data = (await res.json()) as HistoryData

    views.value = data.views
    likesStore().set(data.likes)
  } finally {
    loading.value = false
  }
})

const likes = computed(() => likesStore().users)

const confirmDeleteLike = (username: string) => {
  likesStore().remove(username)
  Api.delete(`/users/${username}/unlike`).send()
  openDialogUsername.value = null
}
</script>

<template>
  <Tabs default-value="likes" class="max-w-3xl h-full m-auto pt-8">
    <TabsList class="grid w-full grid-cols-2">
      <TabsTrigger value="likes">Likes</TabsTrigger>
      <TabsTrigger value="views">Vues</TabsTrigger>
    </TabsList>

    <!-- LIKES -->
    <TabsContent value="likes">
      <Empty v-if="likes.length === 0" text="Aucun like" class="mt-12" />

      <ul v-else class="divide-y">
        <li
          v-for="u in likes"
          :key="u.username"
          class="flex items-center justify-between p-4"
        >
          <div class="flex items-center gap-4 font-medium text-xl">
            <Avatar class="rounded-lg h-12 w-12">
              <AvatarImage :src="u.avatar ?? ''" :alt="u.username" />
              <AvatarFallback>{{ u.first_name?.[0] }}</AvatarFallback>
            </Avatar>
            {{ u.first_name }}
          </div>

          <Dialog
            :open="openDialogUsername === u.username"
            @update:open="v => (openDialogUsername = v ? u.username : null)"
          >
            <DialogTrigger as-child>
              <Button
                variant="ghost"
                size="icon"
                class="text-muted-foreground hover:text-destructive"
              >
                <Heart class="w-6 h-6" />
              </Button>
            </DialogTrigger>
            <DialogContent class="sm:max-w-md">
              <DialogHeader>
                <DialogTitle>Es-tu sûr ?</DialogTitle>
                <DialogDescription>
                  Cette action supprimera le like de {{ u.first_name }}.
                </DialogDescription>
              </DialogHeader>
              <DialogFooter class="gap-2">
                <Button variant="outline" @click="openDialogUsername = null">
                  Annuler
                </Button>
                <Button
                  variant="destructive"
                  @click="confirmDeleteLike(u.username)"
                >
                  <HeartOff class="w-4 h-4 mr-2" />
                  Supprimer
                </Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </li>
      </ul>
    </TabsContent>

    <!-- VIEWS -->
    <TabsContent value="views">
      <Empty
        v-if="!views || views.length === 0"
        text="Aucune vue"
        class="mt-12"
      />

      <ul v-else class="divide-y">
        <li
          v-for="u in views"
          :key="u.username"
          class="flex items-center justify-between p-4"
        >
          <div class="flex items-center gap-4 font-medium text-xl">
            <Avatar class="rounded-lg h-12 w-12">
              <AvatarImage :src="u.avatar ?? ''" :alt="u.username" />
              <AvatarFallback>{{ u.first_name?.[0] }}</AvatarFallback>
            </Avatar>
            {{ u.first_name }}
          </div>
        </li>
      </ul>
    </TabsContent>
  </Tabs>
</template>

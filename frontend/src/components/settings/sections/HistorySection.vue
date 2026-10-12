<script setup>
import { ref, computed, onMounted } from 'vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Badge } from '@/components/ui/badge'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Skeleton } from '@/components/ui/skeleton'
import { Heart, Eye } from 'lucide-vue-next'
import UserItem from '@/components/users/UserItem.vue'
import { useMe } from '@/composable/useMe.ts'

const me = useMe()

const isLoading = ref(false)

const viewedProfiles = ref([])
const likedProfiles = ref([])

function formatRelativeDate(dateString) {
  const date = new Date(dateString)
  const now = new Date()
  const diffMs = now - date
  const diffMin = Math.floor(diffMs / 60000)
  const diffH = Math.floor(diffMin / 60)
  const diffDays = Math.floor(diffH / 24)

  if (diffMin < 60) return `There are ${diffMin} min`
  if (diffH < 24) return `There are ${diffH} h`
  if (diffDays === 1) return 'Yesterday'
  return `There are ${diffDays} days`
}

const viewedCount = computed(() => viewedProfiles.value.length)
const likedCount = computed(() => likedProfiles.value.length)

onMounted(async () => {
  const history = await me.fetchHistory()

  viewedProfiles.value = history.views
  likedProfiles.value = history.likes
})
</script>

<template>
  <div class="w-full">
    <Tabs default-value="viewed" orientation="horizontal">
      <TabsList class="grid w-full grid-cols-2 items-center">
        <TabsTrigger value="viewed" class="flex items-center gap-2">
          <Eye class="h-4 w-4" />
          Vus
          <Badge variant="secondary">{{ viewedCount }}</Badge>
        </TabsTrigger>
        <TabsTrigger value="liked">
          <Heart class="h-4 w-4" />
          Likés
          <Badge variant="secondary">{{ likedCount }}</Badge>
        </TabsTrigger>
      </TabsList>

      <!-- VIEW -->
      <TabsContent value="viewed">
        <ScrollArea class="h-[70vh]">
          <div v-if="isLoading" class="space-y-3">
            <Skeleton v-for="i in 4" :key="i" class="h-20 w-full rounded-xl" />
          </div>

          <div
            v-else-if="viewedProfiles.length === 0"
            class="text-center py-16"
          >
            <Eye class="mx-auto h-10 w-10 text-muted-foreground mb-2" />
            <p class="text-muted-foreground">
              No profiles have been viewed yet
            </p>
          </div>

          <div v-else class="space-y-3">
            <UserItem
              v-for="profile in viewedProfiles"
              :key="profile.id"
              :profile="profile"
              :description="formatRelativeDate(profile.created_at)"
            />
          </div>
        </ScrollArea>
      </TabsContent>

      <!-- LIKES -->
      <TabsContent value="liked">
        <ScrollArea class="h-[70vh]">
          <div v-if="isLoading" class="space-y-3">
            <Skeleton v-for="i in 4" :key="i" class="h-20 w-full rounded-xl" />
          </div>

          <div v-else-if="likedProfiles.length === 0" class="text-center py-16">
            <Heart class="mx-auto h-10 w-10 text-muted-foreground mb-2" />
            <p class="text-muted-foreground">
              You haven't liked any profiles yet
            </p>
          </div>

          <div v-else class="space-y-3">
            <UserItem
              v-for="profile in likedProfiles"
              :key="profile.id"
              :profile="profile"
              :description="formatRelativeDate(profile.created_at)"
            />
          </div>
        </ScrollArea>
      </TabsContent>
    </Tabs>
  </div>
</template>

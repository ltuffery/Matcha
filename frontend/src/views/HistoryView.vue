<script setup>
import { ref, computed } from 'vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Badge } from '@/components/ui/badge'
import { ScrollArea } from '@/components/ui/scroll-area'
import { Skeleton } from '@/components/ui/skeleton'
import { Heart, Eye } from 'lucide-vue-next'
import UserItem from '@/components/users/UserItem.vue'

const isLoading = ref(false)

const viewedProfiles = ref([
  {
    id: 1,
    first_name: 'Emma',
    last_name: 'Martinez',
    age: 27,
    avatar: 'https://i.pravatar.cc/150?img=32',
    viewedAt: '2026-09-16T09:30:00',
    city: 'Paris',
  },
  {
    id: 2,
    first_name: 'Léa',
    last_name: 'Martinez',
    age: 24,
    avatar: 'https://i.pravatar.cc/150?img=45',
    viewedAt: '2026-09-15T18:12:00',
    city: 'Lyon',
  },
  {
    id: 3,
    first_name: 'Chloé',
    last_name: 'Martinez',
    age: 29,
    avatar: 'https://i.pravatar.cc/150?img=47',
    viewedAt: '2026-09-14T21:05:00',
    city: 'Marseille',
  },
])

const likedProfiles = ref([
  {
    id: 4,
    first_name: 'Manon',
    last_name: 'Martinez',
    age: 26,
    avatar: 'https://i.pravatar.cc/150?img=36',
    likedAt: '2026-09-16T08:10:00',
    city: 'Toulouse',
    matched: true,
  },
  {
    id: 5,
    first_name: 'Camille',
    last_name: 'Martinez',
    age: 31,
    avatar: 'https://i.pravatar.cc/150?img=38',
    likedAt: '2026-09-13T14:45:00',
    city: 'Nice',
    matched: false,
  },
])

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
</script>

<template>
  <div class="w-full mx-auto p-4 space-y-6">
    <div>
      <h1 class="text-2xl font-bold tracking-tight">History</h1>
      <p class="text-sm text-muted-foreground">
        See the profiles you've recently viewed and liked
      </p>
    </div>

    <Tabs default-value="viewed" class="w-full">
      <TabsList class="grid w-full grid-cols-2">
        <TabsTrigger value="viewed" class="flex items-center gap-2">
          <Eye class="h-4 w-4" />
          Vus
          <Badge variant="secondary">{{ viewedCount }}</Badge>
        </TabsTrigger>
        <TabsTrigger value="liked" class="flex items-center gap-2">
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
              :description="formatRelativeDate(profile.viewedAt)"
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
              :description="formatRelativeDate(profile.viewedAt)"
            />
          </div>
        </ScrollArea>
      </TabsContent>
    </Tabs>
  </div>
</template>

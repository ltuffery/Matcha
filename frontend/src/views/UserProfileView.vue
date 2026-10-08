<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Heart, MapPin, MessageCircle, Pencil, Sparkles } from 'lucide-vue-next'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from '@/components/ui/carousel'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from '@/components/ui/dialog'
import { ApiClient } from '@/api/client'

interface Profile {
  id: number
  username: string
  age: number
  city: string | null
  biography: string | null
  avatar: string | null
  photos: { id: number; path: string }[]
  tags: string[]
  liked_by_me: boolean
  is_match: boolean
  is_online: boolean
  is_me: boolean
  created_at: string
}

const route = useRoute()
const router = useRouter()
const profile = ref<Profile | null>(null)
const loading = ref(true)
const error = ref('')
const showMatch = ref(false)
const editing = ref(false)
const form = ref({ bio: '', city: '', tags: '' })

async function load() {
  loading.value = true
  error.value = ''
  try {
    const res = await ApiClient.get<Profile>(`/users/${route.params.username}`)
    if (!res.ok())
      throw new Error(
        res.status() === 404 ? 'Profil introuvable' : 'Erreur de chargement',
      )
    profile.value = await res.json()
  } catch (e: any) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function toggleLike() {
  if (!profile.value) return
  const res = await fetch(`/api/profile/${profile.value.id}/like`, {
    method: 'POST',
    credentials: 'include',
  })
  const data = await res.json()
  profile.value.liked_by_me = data.liked
  if (data.is_match && !profile.value.is_match) showMatch.value = true
  profile.value.is_match = data.is_match
}

function openEdit() {
  if (!profile.value) return
  form.value = {
    bio: profile.value.biography ?? '',
    city: profile.value.city ?? '',
    tags: profile.value.tags.join(', '),
  }
  editing.value = true
}

async function saveProfile() {
  const interests = form.value.tags
    .split(',')
    .map(s => s.trim())
    .filter(Boolean)
  await fetch('/api/profile', {
    method: 'PUT',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      bio: form.value.bio,
      city: form.value.city,
      interests,
    }),
  })
  editing.value = false
  load()
}

const initials = (name: string) => name.slice(0, 2).toUpperCase()

onMounted(load)
watch(() => route.params.id, load)
</script>

<template>
  <div class="container max-w-4xl mx-auto py-8 px-4">
    <div v-if="loading" class="space-y-4">
      <Skeleton class="h-80 w-full rounded-xl" />
      <Skeleton class="h-8 w-1/3" />
      <Skeleton class="h-24 w-full" />
    </div>

    <Card v-else-if="error" class="text-center py-12">
      <p class="text-muted-foreground mb-4">{{ error }}</p>
      <Button variant="outline" @click="router.back()">Retour</Button>
    </Card>

    <!-- Profile -->
    <div v-else-if="profile" class="grid gap-6 md:grid-cols-[1.2fr_1fr]">
      <!-- Photos -->
      <Card class="overflow-hidden">
        <Carousel v-if="profile.photos.length" class="w-full">
          <CarouselContent>
            <CarouselItem v-for="p in profile.photos" :key="p.id">
              <img
                :src="p.path"
                :alt="profile.username"
                class="w-full aspect-3/4 object-cover"
              />
            </CarouselItem>
          </CarouselContent>
          <template v-if="profile.photos.length > 1">
            <CarouselPrevious class="left-2" />
            <CarouselNext class="right-2" />
          </template>
        </Carousel>
        <div
          v-else
          class="aspect-3/4 flex items-center justify-center bg-muted"
        >
          <Avatar class="h-32 w-32">
            <AvatarImage v-if="profile.avatar" :src="profile.avatar" />
            <AvatarFallback class="text-3xl">{{
              initials(profile.username)
            }}</AvatarFallback>
          </Avatar>
        </div>
      </Card>

      <!-- Infos -->
      <div class="space-y-4">
        <Card>
          <CardHeader>
            <div class="flex items-center gap-3">
              <Avatar class="h-14 w-14">
                <AvatarImage v-if="profile.avatar" :src="profile.avatar" />
                <AvatarFallback>{{
                  initials(profile.username)
                }}</AvatarFallback>
              </Avatar>
              <div>
                <CardTitle class="text-2xl">
                  {{ profile.username }}, {{ profile.age }}
                </CardTitle>
                <div
                  class="flex items-center gap-2 text-sm text-muted-foreground mt-1"
                >
                  <span v-if="profile.city" class="flex items-center gap-1">
                    <MapPin class="h-4 w-4" /> {{ profile.city }}
                  </span>
                  <span class="flex items-center gap-1">
                    <span
                      class="h-2 w-2 rounded-full"
                      :class="
                        profile.is_online ? 'bg-green-500' : 'bg-gray-400'
                      "
                    />
                    {{ profile.is_online ? 'En ligne' : 'Hors ligne' }}
                  </span>
                </div>
              </div>
            </div>
            <Badge
              v-if="profile.is_match"
              class="w-fit mt-3 bg-pink-500 hover:bg-pink-500"
            >
              <Sparkles class="h-3 w-3 mr-1" /> C'est un match !
            </Badge>
          </CardHeader>

          <CardContent class="flex gap-2">
            <template v-if="profile.is_me">
              <Button class="flex-1" @click="openEdit">
                <Pencil class="h-4 w-4 mr-2" /> Modifier mon profil
              </Button>
            </template>
            <template v-else>
              <Button
                class="flex-1"
                :variant="profile.liked_by_me ? 'default' : 'outline'"
                :class="profile.liked_by_me && 'bg-pink-500 hover:bg-pink-600'"
                @click="toggleLike"
              >
                <Heart
                  class="h-4 w-4 mr-2"
                  :class="profile.liked_by_me && 'fill-current'"
                />
                {{ profile.liked_by_me ? 'Liké' : 'Liker' }}
              </Button>
              <Button
                variant="secondary"
                class="flex-1"
                :disabled="!profile.is_match"
                @click="router.push(`/messages/${profile.id}`)"
              >
                <MessageCircle class="h-4 w-4 mr-2" /> Message
              </Button>
            </template>
          </CardContent>
        </Card>

        <Card>
          <CardHeader><CardTitle class="text-lg">About</CardTitle></CardHeader>
          <CardContent>
            <p class="whitespace-pre-line text-sm">
              {{
                profile.biography ||
                "Cet utilisateur n'a pas encore rédigé de bio."
              }}
            </p>
          </CardContent>
        </Card>

        <Card v-if="profile.tags.length">
          <CardHeader
            ><CardTitle class="text-lg"
              >Centres d'intérêt</CardTitle
            ></CardHeader
          >
          <CardContent class="flex flex-wrap gap-2">
            <Badge v-for="i in profile.tags" :key="i" variant="secondary">{{
              i
            }}</Badge>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Popup de match -->
    <Dialog v-model:open="showMatch">
      <DialogContent class="text-center">
        <DialogHeader>
          <DialogTitle class="text-3xl text-pink-500">Match ! 💘</DialogTitle>
        </DialogHeader>
        <p>{{ profile?.username }} also liked you.</p>
        <DialogFooter class="sm:justify-center">
          <Button
            class="bg-pink-500 hover:bg-pink-600"
            @click="router.push(`/messages/${profile?.id}`)"
          >
            Send a message
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Fenêtre de modification -->
    <Dialog v-model:open="editing">
      <DialogContent>
        <DialogHeader><DialogTitle>Edit my profile</DialogTitle></DialogHeader>
        <div class="space-y-3">
          <Input v-model="form.city" placeholder="City" />
          <Textarea
            v-model="form.bio"
            placeholder="About you..."
            maxlength="500"
            rows="5"
          />
        </div>
        <DialogFooter>
          <Button variant="outline" @click="editing = false">Cancel</Button>
          <Button @click="saveProfile">Save</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>

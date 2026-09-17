<script setup lang="ts">
import { ref } from 'vue'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
import {
  Heart,
  MessageCircle,
  MapPin,
  Briefcase,
  GraduationCap,
  Ruler,
  Flag,
  MoreVertical,
} from 'lucide-vue-next'

interface Profile {
  id: string
  firstName: string
  age: number
  city: string
  distance: number
  job: string
  education: string
  height: number
  bio: string
  photos: string[]
  interests: string[]
  isOnline: boolean
  lastSeen?: string
}

const profile = ref<Profile>({
  id: '1',
  firstName: 'Emma',
  age: 27,
  city: 'Paris',
  distance: 5,
  job: 'UX Designer',
  education: 'École des Beaux-Arts',
  height: 168,
  bio: "Passionnée de voyages, de bons restaurants et de randonnées le week-end. À la recherche de quelqu'un pour partager de belles aventures ✨",
  photos: [
    'https://picsum.photos/seed/emma1/600/800',
    'https://picsum.photos/seed/emma2/600/800',
    'https://picsum.photos/seed/emma3/600/800',
  ],
  interests: ['Voyages', 'Photographie', 'Randonnée', 'Cuisine', 'Cinéma', 'Yoga'],
  isOnline: true,
})

const currentPhotoIndex = ref(0)

function nextPhoto() {
  currentPhotoIndex.value =
    (currentPhotoIndex.value + 1) % profile.value.photos.length
}

function prevPhoto() {
  currentPhotoIndex.value =
    (currentPhotoIndex.value - 1 + profile.value.photos.length) %
    profile.value.photos.length
}

function handleLike() {
  console.log('Like envoyé à', profile.value.firstName)
}

function handleMessage() {
  console.log('Ouvrir la messagerie avec', profile.value.firstName)
}
</script>

<template>
  <div class="max-w-md mx-auto min-h-screen bg-background">
    <Card class="overflow-hidden border-0 rounded-none sm:rounded-xl sm:border sm:my-6">
      <!-- Galerie photo -->
      <div class="relative aspect-[3/4] bg-muted">
        <img
          :src="profile.photos[currentPhotoIndex]"
          :alt="profile.firstName"
          class="w-full h-full object-cover"
        />

        <!-- Indicateurs de photos -->
        <div class="absolute top-2 left-2 right-2 flex gap-1">
          <div
            v-for="(photo, i) in profile.photos"
            :key="i"
            class="h-1 flex-1 rounded-full transition-colors"
            :class="i === currentPhotoIndex ? 'bg-white' : 'bg-white/40'"
          />
        </div>

        <!-- Zones cliquables pour naviguer -->
        <button
          class="absolute left-0 top-0 h-full w-1/2"
          @click="prevPhoto"
        />
        <button
          class="absolute right-0 top-0 h-full w-1/2"
          @click="nextPhoto"
        />

        <!-- Badge en ligne -->
        <Badge
          v-if="profile.isOnline"
          class="absolute top-4 right-4 bg-green-500 hover:bg-green-500 text-white gap-1"
        >
          <span class="h-2 w-2 rounded-full bg-white animate-pulse" />
          En ligne
        </Badge>

        <!-- Overlay dégradé + nom -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
          <div class="flex items-end justify-between">
            <div>
              <h1 class="text-2xl font-bold text-white">
                {{ profile.firstName }}, {{ profile.age }}
              </h1>
              <p class="flex items-center gap-1 text-white/90 text-sm mt-1">
                <MapPin class="h-4 w-4" />
                {{ profile.city }} · à {{ profile.distance }} km
              </p>
            </div>
            <Button
              size="icon"
              variant="ghost"
              class="text-white hover:bg-white/20 hover:text-white"
            >
              <MoreVertical class="h-5 w-5" />
            </Button>
          </div>
        </div>
      </div>

      <CardContent class="p-4 space-y-5">
        <!-- Infos rapides -->
        <div class="flex flex-wrap gap-2">
          <Badge variant="secondary" class="gap-1 py-1.5">
            <Briefcase class="h-3.5 w-3.5" />
            {{ profile.job }}
          </Badge>
          <Badge variant="secondary" class="gap-1 py-1.5">
            <GraduationCap class="h-3.5 w-3.5" />
            {{ profile.education }}
          </Badge>
          <Badge variant="secondary" class="gap-1 py-1.5">
            <Ruler class="h-3.5 w-3.5" />
            {{ profile.height }} cm
          </Badge>
        </div>

        <Separator />

        <!-- Bio -->
        <div>
          <h2 class="font-semibold mb-2">À propos</h2>
          <p class="text-sm text-muted-foreground leading-relaxed">
            {{ profile.bio }}
          </p>
        </div>

        <Separator />

        <!-- Centres d'intérêt -->
        <div>
          <h2 class="font-semibold mb-2">Centres d'intérêt</h2>
          <div class="flex flex-wrap gap-2">
            <Badge
              v-for="interest in profile.interests"
              :key="interest"
              variant="outline"
              class="rounded-full"
            >
              {{ interest }}
            </Badge>
          </div>
        </div>

        <Separator />

        <!-- Photos supplémentaires en grille -->
        <div>
          <h2 class="font-semibold mb-2">Photos</h2>
          <div class="grid grid-cols-3 gap-2">
            <button
              v-for="(photo, i) in profile.photos"
              :key="i"
              class="aspect-square rounded-lg overflow-hidden ring-2 ring-transparent"
              :class="{ 'ring-primary': i === currentPhotoIndex }"
              @click="currentPhotoIndex = i"
            >
              <img :src="photo" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Barre d'actions flottante -->
    <div class="sticky bottom-0 bg-background/95 backdrop-blur border-t p-4 flex justify-center gap-4">
      <Button
        size="icon"
        variant="outline"
        class="h-14 w-14 rounded-full border-destructive/30 text-destructive hover:bg-destructive/10"
      >
        <Flag class="h-6 w-6" />
      </Button>
      <Button
        size="icon"
        class="h-16 w-16 rounded-full bg-pink-500 hover:bg-pink-600 shadow-lg"
        @click="handleLike"
      >
        <Heart class="h-7 w-7" />
      </Button>
      <Button
        size="icon"
        variant="outline"
        class="h-14 w-14 rounded-full"
        @click="handleMessage"
      >
        <MessageCircle class="h-6 w-6" />
      </Button>
    </div>
  </div>
</template>

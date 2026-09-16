<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
  PlayingCardsFan,
  Search,
  Heart,
  MessageCircle,
  Settings,
} from '@lucide/vue'

interface NavItem {
  label: string
  icon: any
  path: string
}

const navItems: NavItem[] = [
  { label: 'Swipe', icon: PlayingCardsFan, path: '/home' },
  { label: 'Search', icon: Search, path: '/search' },
  { label: 'Likes', icon: Heart, path: '/matches' },
  { label: 'Chat', icon: MessageCircle, path: '/messages' },
  { label: 'Settings', icon: Settings, path: '/settings' },
]

const route = useRoute()
const router = useRouter()

const isActive = (path: string) => {
  return path === '/' ? route.path === '/' : route.path.startsWith(path)
}

const goTo = (path: string) => router.push(path)
</script>

<template>
  <!-- ===== Desktop / tablette : sidebar verticale ===== -->
  <nav
    class="hidden md:flex md:flex-col md:justify-between md:items-center lg:items-stretch md:w-20 lg:w-64 md:h-screen md:sticky md:top-0 border-r bg-background"
  >
    <div class="flex flex-col gap-2 p-3 mt-4">
      <button
        v-for="item in navItems"
        :key="item.path"
        @click="goTo(item.path)"
        :class="[
          'flex items-center gap-3 rounded-xl px-3 py-2.5 transition-colors',
          'hover:bg-muted',
          isActive(item.path)
            ? 'bg-primary/10 text-primary font-medium'
            : 'text-muted-foreground',
        ]"
      >
        <component :is="item.icon" class="h-6 w-6 shrink-0 md:items-center" />
        <span class="hidden lg:inline text-sm">{{ item.label }}</span>
      </button>
    </div>
  </nav>

  <!-- ===== Mobile : bottom navbar ===== -->
  <nav
    class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t bg-background/95 backdrop-blur supports-backdrop-filter:bg-background/80"
  >
    <div
      class="flex items-center justify-around h-16 px-2 pb-[env(safe-area-inset-bottom)]"
    >
      <button
        v-for="item in navItems"
        :key="item.path"
        @click="goTo(item.path)"
        class="flex flex-col items-center justify-center gap-1 flex-1 h-full text-xs transition-colors"
        :class="isActive(item.path) ? 'text-primary' : 'text-muted-foreground'"
      >
        <component
          :is="item.icon"
          class="h-6 w-6"
          :class="isActive(item.path) && 'fill-primary/20'"
        />
        <span class="leading-none">{{ item.label }}</span>
      </button>
    </div>
  </nav>
</template>

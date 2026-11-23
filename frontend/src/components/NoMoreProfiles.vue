<script setup lang="ts">
import { SearchSlash } from '@lucide/vue';

defineEmits<{ (e: 'refresh'): void }>()
</script>

<template>
  <div
    class="flex h-full w-full flex-col items-center justify-center gap-8 rounded-lg border border-white/10 bg-neutral-900 p-8 text-center"
  >
    <!-- Icône -->
    <div class="relative flex size-28 items-center justify-center">
      <span class="absolute inset-0 animate-pulse rounded-full bg-pink-500/10"></span>
      <span class="absolute inset-3 rounded-full bg-pink-500/15"></span>
      <SearchSlash class="relative size-12 text-pink-400" />
    </div>

    <!-- Texte -->
    <div class="space-y-2">
      <h2 class="text-2xl font-semibold tracking-tight">Tu as fait le tour !</h2>
      <p class="mx-auto max-w-xs text-sm text-white/60">
        Il n'y a plus de profils pour le moment. Élargis tes préférences ou reviens un peu plus tard.
      </p>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap items-center justify-center gap-3">
      <RouterLink
        to="/settings"
        class="flex items-center bg-primary/10 text-primary font-medium gap-3 rounded-xl px-3 py-2.5 transition-colors hover:bg-muted"
      >
        Modifier mes préférences
      </RouterLink>
    </div>
  </div>
</template>

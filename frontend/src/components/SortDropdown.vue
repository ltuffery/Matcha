<script setup lang="ts">
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent } from "@/components/ui/dropdown-menu"
import { Button } from "./ui/button";
import { ToggleGroup, ToggleGroupItem } from "./ui/toggle-group";
import { ArrowDown, ArrowUp, Cake, Hash, MapPin, TrendingUp, SlidersHorizontal } from "@lucide/vue";
</script>

<template>
  <div class="absolute right-4 top-8 z-50">
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button
          size="icon"
          class="relative size-11 rounded-full border border-white/20 bg-black/30 text-white shadow-lg backdrop-blur-xl transition-all duration-200 hover:scale-105 hover:bg-black/40 active:scale-95"
        >
          <SlidersHorizontal class="size-5" />
          <span
            class="absolute -right-0.5 -top-0.5 size-3 rounded-full bg-linear-to-br from-rose-400 to-fuchsia-500 ring-2 ring-black/40"
          />
        </Button>
      </DropdownMenuTrigger>

      <DropdownMenuContent
        align="end"
        :side-offset="10"
        class="w-72 rounded-2xl border-white/10 bg-zinc-900/80 p-2 text-white shadow-2xl backdrop-blur-2xl"
      >

        <div class="flex items-center justify-between px-2 pb-2 pt-1">
          <span class="text-sm font-semibold tracking-tight"
            >Trier par</span
          >
          <Button variant="link" > Réinitialiser </Button>
        </div>



        <ToggleGroup class="flex flex-col w-full gap-3" type="single">
          <ToggleGroupItem
            className="
              w-full rounded-xl border border-transparent
              transition-colors
              hover:bg-white/5
              data-[state=on]:border-white/20
              data-[state=on]:bg-white/10
              data-[state=on]:text-white
            "
            value="age">
            <div class="flex w-full items-center justify-between rounded-xl px-2 py-2 transition-colors hover:bg-white/5">
              <div class="flex items-center gap-3">
                <div
                  class="flex size-8 items-center justify-center rounded-lg bg-white/10"
                >
                  <Cake class="size-4 text-white/80" />
                </div>
                <span class="text-sm font-medium">Age</span>
              </div>
            </div>
          </ToggleGroupItem>

          <ToggleGroupItem
            className="
                w-full rounded-xl border border-transparent
                transition-colors
                hover:bg-white/5
                data-[state=on]:border-white/20
                data-[state=on]:bg-white/10
                data-[state=on]:text-white
              "
            value="dist">
            <div class="flex w-full items-center justify-between rounded-xl px-2 py-2 transition-colors hover:bg-white/5">
              <div class="flex items-center gap-3">
                <div
                  class="flex size-8 items-center justify-center rounded-lg bg-white/10"
                >
                  <MapPin class="size-4 text-white/80" />
                </div>
                <span class="text-sm font-medium">Distance</span>
              </div>
            </div>
          </ToggleGroupItem>

          <ToggleGroupItem
            className="
                w-full rounded-xl border border-transparent
                transition-colors
                hover:bg-white/5
                data-[state=on]:border-white/20
                data-[state=on]:bg-white/10
                data-[state=on]:text-white
              "
              value="fame">
            <div class="flex w-full items-center justify-between rounded-xl px-2 py-2 transition-colors hover:bg-white/5">
              <div class="flex items-center gap-3">
                <div
                  class="flex size-8 items-center justify-center rounded-lg bg-white/10"
                >
                  <TrendingUp class="size-4 text-white/80" />
                </div>
                <span class="text-sm font-medium">Popularité</span>
              </div>
            </div>
          </ToggleGroupItem>

          <ToggleGroupItem
            className="
                w-full rounded-xl border border-transparent
                transition-colors
                hover:bg-white/5
                data-[state=on]:border-white/20
                data-[state=on]:bg-white/10
                data-[state=on]:text-white
              "
              value="tags">
            <div class="flex w-full items-center justify-between rounded-xl px-2 py-2 transition-colors hover:bg-white/5">
              <div class="flex items-center gap-3">
                <div
                  class="flex size-8 items-center justify-center rounded-lg bg-white/10"
                >
                  <Hash class="size-4 text-white/80" />
                </div>
                <span class="text-sm font-medium">Tag Communs</span>
              </div>
            </div>
          </ToggleGroupItem>
        </ToggleGroup>
      </DropdownMenuContent>
    </DropdownMenu>
  </div>
</template>

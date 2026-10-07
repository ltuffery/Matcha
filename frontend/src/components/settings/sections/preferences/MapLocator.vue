<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import {
  LocateFixed,
  Search,
  Loader2,
  MapPin,
  ChevronDown,
  CheckIcon,
} from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Map,
  MapMarker,
  MarkerContent,
  MarkerTooltip,
  MarkerPopup,
} from '@/components/ui/map'
import CircleMapLayer from '@/components/settings/sections/preferences/CircleMapLayer.vue'
import {
  Popover,
  PopoverAnchor,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import {
  ListboxRoot,
  ListboxContent,
  ListboxItemIndicator,
  ListboxItem,
} from 'reka-ui'

export interface Location {
  lat: number
  lng: number
  city: string
}

interface GeoAddress {
  fulltext: string
  x: number // lon
  y: number // lat
  city?: string
  zipcode?: string
  street?: string
  kind?: string
}

const model = defineModel<Location | null>({ default: null })

const DEFAULT: [number, number] = [2.3522, 48.8566] // Paris [lng, lat]
const loading = ref(false)
const tracking = ref(false)
const error = ref('')
const mapKey = ref(0)
const open = ref(false)
const searchTerm = ref('')
const currentLabel = computed(() => model.value?.city || undefined)
const addresses = ref<GeoAddress[]>([])
let debounceTimer: ReturnType<typeof setTimeout> | undefined
let controller: AbortController | undefined

const center = computed<[number, number]>(() =>
  model.value ? [model.value.lng, model.value.lat] : DEFAULT,
)

async function reverseGeocode(lat: number, lng: number): Promise<string> {
  try {
    const r = await fetch(
      `https://data.geopf.fr/geocodage/reverse?lon=${lng}&lat=${lat}&index=address&limit=10&returntruegeometry=false&type=housenumber`,
    )
    const d = await r.json()
    const address = d.features[0]

    console.log(address.properties.label)

    return address.properties.label
  } catch (e: any) {
    console.log(e.message)
    return ''
  }
}

async function setPosition(
  lat: number,
  lng: number,
  recenter = true,
  city?: string,
) {
  model.value = { lat, lng, city: city ?? (await reverseGeocode(lat, lng)) }

  if (recenter) mapKey.value++
}

function geoErrorMessage(e: GeolocationPositionError) {
  return (
    {
      1: "Vous avez refusé l'accès à votre position.",
      2: 'Position indisponible.',
      3: 'Délai dépassé, réessayez.',
    }[e.code] ?? 'Erreur de géolocalisation.'
  )
}

// 1. Localisation ponctuelle
function locateMe() {
  if (!('geolocation' in navigator)) {
    error.value =
      "La géolocalisation n'est pas prise en charge par votre navigateur."
    return
  }
  loading.value = true
  error.value = ''
  navigator.geolocation.getCurrentPosition(
    async pos => {
      await setPosition(pos.coords.latitude, pos.coords.longitude)
      loading.value = false
    },
    e => {
      error.value = geoErrorMessage(e)
      loading.value = false
    },
    { enableHighAccuracy: true, timeout: 15000 },
  )
}

async function autoCompleteAddress(address: string) {
  controller?.abort()
  controller = new AbortController()
  loading.value = true
  error.value = ''
  try {
    const r = await fetch(
      `https://data.geopf.fr/geocodage/completion/?text=${encodeURIComponent(address)}&maxResponses=8`,
      { signal: controller.signal },
    )
    const data = await r.json()
    addresses.value = data.results ?? []
    open.value = true
  } catch (e: any) {
    if (e.name !== 'AbortError') error.value = e.message
  } finally {
    loading.value = false
  }
}

// Délai de 300 ms pour ne pas envoyer une requête à chaque touche
watch(searchTerm, q => {
  clearTimeout(debounceTimer)
  if (q.trim().length < 3) {
    addresses.value = []
    return
  }
  debounceTimer = setTimeout(() => autoCompleteAddress(q), 300)
})

async function onSelectAddress(value: unknown) {
  const a = addresses.value.find(ad => ad.fulltext === value)
  if (!a) return
  open.value = false
  searchTerm.value = ''
  try {
    await setPosition(a.y, a.x, true, a.city || a.fulltext)
  } catch (e: any) {
    error.value = e.message
  }
}
</script>

<template>
  <div class="space-y-3">
    <div class="flex items-center justify-between text-sm">
      <Label>Localisation</Label>
      <span
        v-if="model?.city"
        class="flex items-center gap-1 text-muted-foreground"
      >
        <MapPin class="h-3 w-3" /> {{ model.city }}
      </span>
    </div>

    <div class="flex gap-3">
      <Popover v-model:open="open">
        <ListboxRoot
          :model-value="currentLabel"
          highlight-on-hover
          class="w-full"
          @update:model-value="onSelectAddress"
        >
          <PopoverAnchor class="relative flex w-full items-center">
            <Search
              class="pointer-events-none absolute left-3 size-4 text-muted-foreground"
            />

            <Input
              v-model="searchTerm"
              :placeholder="currentLabel ?? 'Choisir une adresse manuellement'"
              class="pl-9 pr-10"
              @focus="open = true"
              @input="open = true"
              @keydown.esc="open = false"
              @keydown.enter.prevent
            />

            <PopoverTrigger as-child>
              <Button
                type="button"
                size="icon"
                variant="ghost"
                class="absolute right-1 size-7"
              >
                <ChevronDown class="size-3.5" />
              </Button>
            </PopoverTrigger>
          </PopoverAnchor>

          <PopoverContent
            class="w-(--reka-popover-trigger-width) p-1"
            align="start"
            @open-auto-focus.prevent
          >
            <ListboxContent
              class="max-h-75 scroll-py-1 overflow-x-hidden overflow-y-auto empty:after:content-['No_options'] empty:p-1 empty:after:block"
              tabindex="0"
            >
              <ListboxItem
                v-for="address in addresses"
                :key="address.fulltext"
                :value="address.fulltext"
                class="data-highlighted:bg-accent data-highlighted:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-disabled:pointer-events-none data-disabled:opacity-50 [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4"
              >
                <MapPin class="text-muted-foreground" />
                <span class="truncate">{{ address.fulltext }}</span>
                <ListboxItemIndicator
                  class="ml-auto inline-flex items-center justify-center"
                >
                  <CheckIcon />
                </ListboxItemIndicator>
              </ListboxItem>
            </ListboxContent>
          </PopoverContent>
        </ListboxRoot>
      </Popover>

      <Button
        variant="outline"
        size="icon"
        :disabled="loading || tracking"
        @click="locateMe"
      >
        <Loader2 v-if="loading" class="animate-spin" />
        <LocateFixed v-else />
      </Button>
    </div>

    <p v-if="error" class="text-sm text-destructive">{{ error }}</p>

    <div class="h-80 overflow-hidden rounded-lg border">
      <Map :key="mapKey" :center="center" :zoom="15" class="rounded">
        <CircleMapLayer v-if="model" />

        <MapMarker v-if="model" :longitude="model.lng" :latitude="model.lat">
          <MarkerContent>
            <div class="relative">
              <div
                v-if="tracking"
                class="absolute inset-0 size-4 animate-ping rounded-full bg-primary/60"
              />
              <div
                class="relative size-4 rounded-full border-2 border-white bg-primary shadow-lg"
              />
            </div>
          </MarkerContent>
          <MarkerTooltip>{{ model.city || 'Ma position' }}</MarkerTooltip>
          <MarkerPopup>
            <div class="space-y-1">
              <p class="font-medium text-foreground">
                {{ model.city || 'Ma position' }}
              </p>
              <p class="text-xs text-muted-foreground">
                {{ model.lat.toFixed(4) }}, {{ model.lng.toFixed(4) }}
              </p>
            </div>
          </MarkerPopup>
        </MapMarker>
      </Map>
    </div>
  </div>
</template>

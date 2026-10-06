<script setup lang="ts">
import { ref, computed, onBeforeUnmount, watch } from 'vue'
import {
  LocateFixed,
  Navigation,
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
import CircleLayer from '@/components/settings/sections/preferences/CircleLayer.vue'
import { ButtonGroup } from '@/components/ui/button-group'
import {
  Combobox,
  ComboboxAnchor,
  ComboboxEmpty,
  ComboboxGroup,
  ComboboxInput,
  ComboboxItem,
  ComboboxItemIndicator,
  ComboboxList,
  ComboboxTrigger,
} from '@/components/ui/combobox'
import {
  Popover,
  PopoverAnchor,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'

export interface Location {
  lat: number
  lng: number
  city: string
}

const model = defineModel<Location | null>({ default: null })

const DEFAULT: [number, number] = [2.3522, 48.8566] // Paris [lng, lat]
const loading = ref(false)
const tracking = ref(false)
const error = ref('')
const query = ref('')
const mapKey = ref(0)
const timeoutId = ref<ReturnType<typeof setTimeout> | null>(null)
let watchId: number | null = null

const center = computed<[number, number]>(() =>
  model.value ? [model.value.lng, model.value.lat] : DEFAULT,
)

// Retrouver la ville à partir des coordonnées
async function reverseGeocode(lat: number, lng: number): Promise<string> {
  try {
    const r = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&accept-language=fr`,
    )
    const d = await r.json()
    return (
      d.address?.city ||
      d.address?.town ||
      d.address?.village ||
      d.address?.municipality ||
      ''
    )
  } catch {
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
    { enableHighAccuracy: true, timeout: 10000 },
  )
}

async function searchAddress() {
  if (!query.value.trim()) return
  loading.value = true
  error.value = ''
  try {
    const r = await fetch(
      `https://nominatim.openstreetmap.org/search?format=json&limit=1&addressdetails=1&accept-language=fr&q=${encodeURIComponent(query.value)}`,
    )
    const [res] = await r.json()
    if (!res) throw new Error('Adresse introuvable')
    const a = res.address ?? {}
    await setPosition(
      +res.lat,
      +res.lon,
      true,
      a.city || a.town || a.village || query.value,
    )
  } catch (e: any) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
interface GeoAddress {
  fulltext: string
  x: number // longitude
  y: number // latitude
  city?: string
  zipcode?: string
  street?: string
  kind?: string
}

const open = ref(false)
const searchTerm = ref('')
const selected = ref<string>()
const addresses = ref<GeoAddress[]>([])
let debounceTimer: ReturnType<typeof setTimeout> | undefined
let controller: AbortController | undefined

async function autoCompleteAddress(address: string) {
  controller?.abort() // annule la requête précédente
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
  selected.value = a.fulltext
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
          :model-value="selected"
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
              :placeholder="selected ?? 'Choisir une adresse manuellement'"
              class="pl-9 pr-10"
              autocomplete="off"
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
              class="max-h-[300px] scroll-py-1 overflow-x-hidden overflow-y-auto empty:after:content-['No_options'] empty:p-1 empty:after:block"
              tabindex="0"
            >
              <ListboxItem
                v-for="address in addresses"
                :key="address.fulltext"
                :value="address.fulltext"
                class="data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground [&_svg:not([class*=\'text-\'])]:text-muted-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4"
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
      <Map :key="mapKey" :center="center" :zoom="model ? 13 : 5">
        <CircleLayer />

        <MapMarker
          v-if="model"
          :longitude="model.lng"
          :latitude="model.lat"
          draggable
        >
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

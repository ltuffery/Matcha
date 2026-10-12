<script setup lang="ts">
import {
  type MapMouseEvent,
  type GeoJSONSource,
  MapLibreMap,
} from 'maplibre-gl'
import { onBeforeUnmount, ref, watch } from 'vue'
import { useMap } from '@/components/ui/map'
import type { FeatureCollection, Polygon } from 'geojson'
import circle from '@turf/circle'

const props = defineProps<{
  distance_meters?: number
  center?: [number, number] // lon, lat
}>()

const { map, isLoaded } = useMap()
const hoveredPark = ref<string | null>(null)
let cleanup: (() => void) | null = null

function buildZone(m: MapLibreMap): FeatureCollection<Polygon> {
  const center = props.center ?? (m.getCenter().toArray() as [number, number])

  const zone = circle(center, props.distance_meters ?? 100, {
    steps: 64,
    units: 'meters',
  })
  return { type: 'FeatureCollection', features: [zone] }
}

function fitZone(m: MapLibreMap, data: FeatureCollection<Polygon>) {
  const coords = data.features[0].geometry.coordinates[0]
  const lons = coords.map(c => c[0])
  const lats = coords.map(c => c[1])
  m.fitBounds(
    [
      [Math.min(...lons), Math.min(...lats)],
      [Math.max(...lons), Math.max(...lats)],
    ],
    { padding: 20, duration: 300 },
  )
}

watch(
  [map, isLoaded],
  ([m, loaded]) => {
    cleanup?.()
    cleanup = null
    if (!m || !loaded) return

    const data = buildZone(m)
    if (!m.getSource('parks')) m.addSource('parks', { type: 'geojson', data })
    if (!m.getLayer('parks-fill')) {
      m.addLayer({
        id: 'parks-fill',
        type: 'fill',
        source: 'parks',
        paint: { 'fill-color': '#ff2056', 'fill-opacity': 0.4 },
      })
    }
    if (!m.getLayer('parks-outline')) {
      m.addLayer({
        id: 'parks-outline',
        type: 'line',
        source: 'parks',
        paint: { 'line-color': '#ec003f', 'line-width': 2 },
      })
    }
    fitZone(m, data)

    const onEnter = () => (m.getCanvas().style.cursor = 'pointer')
    const onLeave = () => {
      m.getCanvas().style.cursor = ''
      hoveredPark.value = null
    }
    const onMove = (_e: MapMouseEvent) =>
      (hoveredPark.value = 'Zone de recherche')

    m.on('mouseenter', 'parks-fill', onEnter)
    m.on('mouseleave', 'parks-fill', onLeave)
    m.on('mousemove', 'parks-fill', onMove)

    cleanup = () => {
      m.off('mouseenter', 'parks-fill', onEnter)
      m.off('mouseleave', 'parks-fill', onLeave)
      m.off('mousemove', 'parks-fill', onMove)
      try {
        if (m.getLayer('parks-outline')) m.removeLayer('parks-outline')
        if (m.getLayer('parks-fill')) m.removeLayer('parks-fill')
        if (m.getSource('parks')) m.removeSource('parks')
      } catch {}
    }
  },
  { immediate: true },
)

watch(
  () => [props.distance_meters, props.center?.[0], props.center?.[1]],
  () => {
    const m = map.value
    if (!m || !isLoaded.value) return
    const source = m.getSource('parks') as GeoJSONSource | undefined
    if (!source) return
    const data = buildZone(m)
    source.setData(data)
    fitZone(m, data)
  },
)

onBeforeUnmount(() => cleanup?.())
</script>

<template>
  <div
    v-if="hoveredPark"
    class="bg-background/90 absolute bottom-3 left-3 z-10 rounded-md border px-3 py-2 text-sm font-medium backdrop-blur"
  >
    {{ hoveredPark }}
  </div>
</template>

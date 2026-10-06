<script setup lang="ts">
import type { MapMouseEvent } from 'maplibre-gl'
import { onBeforeUnmount, ref, watch } from 'vue'
import { useMap } from '@/components/ui/map'
import type { FeatureCollection, GeoJSON, Polygon } from 'geojson'
import circle from '@turf/circle'

const { map, isLoaded } = useMap()
const hoveredPark = ref<string | null>(null)

let cleanup: (() => void) | null = null

watch([map, isLoaded], ([m, loaded]) => {
  cleanup?.()
  cleanup = null
  if (!m || !loaded) return

  const center = m.getCenter().toArray()
  const zone = circle(center, 100, { steps: 64, units: 'meters' })

  const geojsonData: FeatureCollection<Polygon> = {
    type: 'FeatureCollection',
    features: [zone as GeoJSON.Feature<Polygon>],
  }

  if (!m.getSource('parks')) {
    m.addSource('parks', { type: 'geojson', data: geojsonData })
  }
  if (!m.getLayer('parks-fill')) {
    m.addLayer({
      id: 'parks-fill',
      type: 'fill',
      source: 'parks',
      paint: { 'fill-color': '#ff2056', 'fill-opacity': 0.4 },
      layout: { visibility: 'visible' },
    })
  }
  if (!m.getLayer('parks-outline')) {
    m.addLayer({
      id: 'parks-outline',
      type: 'line',
      source: 'parks',
      paint: { 'line-color': '#ec003f', 'line-width': 2 },
      layout: { visibility: 'visible' },
    })
  }

  const onEnter = () => {
    m.getCanvas().style.cursor = 'pointer'
  }
  const onLeave = () => {
    m.getCanvas().style.cursor = ''
    hoveredPark.value = null
  }
  const onMove = (e: MapMouseEvent) => {
    const features = m.queryRenderedFeatures(e.point, {
      layers: ['parks-fill'],
    })
    const f = features[0]
    if (f) hoveredPark.value = 'test ?'
  }

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
    } catch {
      // ignore
    }
  }
})

onBeforeUnmount(() => cleanup?.())

const toggleLayer = () => {
  const m = map.value
  if (!m) return
  const visibility = 'visible'

  if (m.getLayer('parks-fill'))
    m.setLayoutProperty('parks-fill', 'visibility', visibility)
  if (m.getLayer('parks-outline'))
    m.setLayoutProperty('parks-outline', 'visibility', visibility)
}
</script>

<template>
  <div
    v-if="hoveredPark"
    class="bg-background/90 absolute bottom-3 left-3 z-10 rounded-md border px-3 py-2 text-sm font-medium backdrop-blur"
  >
    {{ hoveredPark }}
  </div>
</template>

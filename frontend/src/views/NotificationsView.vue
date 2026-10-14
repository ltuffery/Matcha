<script setup lang="ts">
import { computed } from 'vue'
import type { NotificationData } from '@/types'
import { useNotificationStore } from '@/store/useNotificationStore'

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback } from '@/components/ui/avatar'
import { Separator } from '@/components/ui/separator'
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip'
import { Eye, BellOff } from 'lucide-vue-next'

const store = useNotificationStore()

/** Parse une date PHP "YYYY-MM-DD HH:mm:ss" de façon fiable (Safari compris) */
const parseDate = (value: string) => new Date(value.replace(' ', 'T'))

/** Clé du jour en heure locale : YYYY-MM-DD */
const dayKey = (date: Date) =>
  `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`

const dayLabel = (date: Date) => {
  const today = new Date()
  const yesterday = new Date()
  yesterday.setDate(today.getDate() - 1)

  if (dayKey(date) === dayKey(today)) return "Aujourd'hui"
  if (dayKey(date) === dayKey(yesterday)) return 'Hier'

  const label = date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined,
  })
  return label.charAt(0).toUpperCase() + label.slice(1)
}

const formatTime = (date: Date) =>
  date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })

const initials = (username?: string) =>
  (username ?? '?').slice(0, 2).toUpperCase()

interface DayGroup {
  key: string
  label: string
  items: NotificationData[]
  unread: number
}

const groupedNotifications = computed<DayGroup[]>(() => {
  const sorted = [...store.notifications].sort(
    (a, b) =>
      parseDate(b.data.created_at).getTime() -
      parseDate(a.data.created_at).getTime(),
  )

  const groups = new Map<string, DayGroup>()

  for (const notification of sorted) {
    const date = parseDate(notification.data.created_at)
    const key = dayKey(date)

    if (!groups.has(key)) {
      groups.set(key, { key, label: dayLabel(date), items: [], unread: 0 })
    }

    const group = groups.get(key)!
    group.items.push(notification)
    if (!notification.data.view) group.unread++
  }

  return [...groups.values()]
})

const markAsRead = async (notification: NotificationData) => {
  await store.markAsViewed(notification.id as number)
}
</script>

<template>
  <TooltipProvider>
    <div class="w-full space-y-6 p-4">
      <h1 class="text-2xl font-bold tracking-tight">Notifications</h1>

      <!-- État vide -->
      <Card v-if="groupedNotifications.length === 0">
        <CardContent
          class="flex flex-col items-center justify-center gap-2 py-12 text-muted-foreground"
        >
          <BellOff class="h-10 w-10" />
          <p>Aucune notification pour le moment</p>
        </CardContent>
      </Card>

      <!-- Sections par jour -->
      <Card v-for="group in groupedNotifications" :key="group.key">
        <CardHeader
          class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
          <CardTitle class="text-base font-semibold">
            {{ group.label }}
          </CardTitle>
          <Badge v-if="group.unread > 0" variant="secondary">
            {{ group.unread }} non lue{{ group.unread > 1 ? 's' : '' }}
          </Badge>
        </CardHeader>

        <CardContent class="p-0">
          <template
            v-for="(notification, i) in group.items"
            :key="notification.id"
          >
            <Separator v-if="i > 0" />

            <div
              class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-muted/50"
              :class="{ 'bg-primary/5': !notification.data.view }"
            >
              <!-- Point non lu -->
              <span
                class="h-2 w-2 shrink-0 rounded-full"
                :class="
                  notification.data.view ? 'bg-transparent' : 'bg-primary'
                "
              />

              <Avatar>
                <AvatarFallback>
                  {{ initials(notification.data.username) }}
                </AvatarFallback>
              </Avatar>

              <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2">
                  <span class="font-semibold">{{
                    notification.data.username
                  }}</span>
                  <span class="text-xs text-muted-foreground">
                    {{ formatTime(parseDate(notification.data.created_at)) }}
                  </span>
                </div>
                <p
                  class="truncate text-sm"
                  :class="
                    notification.data.view
                      ? 'text-muted-foreground'
                      : 'text-foreground'
                  "
                >
                  {{ notification.data.content }}
                </p>
              </div>

              <Tooltip v-if="!notification.data.view">
                <TooltipTrigger as-child>
                  <Button
                    variant="ghost"
                    size="icon"
                    @click="markAsRead(notification)"
                  >
                    <Eye class="h-5 w-5" />
                    <span class="sr-only">Marquer comme lu</span>
                  </Button>
                </TooltipTrigger>
                <TooltipContent>Marquer comme lu</TooltipContent>
              </Tooltip>
            </div>
          </template>
        </CardContent>
      </Card>
    </div>
  </TooltipProvider>
</template>

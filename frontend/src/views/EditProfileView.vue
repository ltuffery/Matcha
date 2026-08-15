<script setup lang="ts">
import ImageSelector from '@/components/userForm/ImageSelector.vue'
import TagSelector from '@/components/TagSelector.vue'
import { useUserInfoStore } from '@/store/userInfo'
import { Api } from '@/utils/api'
import { onBeforeMount, onUnmounted, ref } from 'vue'
import type { User } from '@/types'

interface ImageItem {
  file: File | null
  url: string
}

const userInfoStore = useUserInfoStore()
const info = ref<{
  biography: string | undefined
  photos: ImageItem[]
  tags: string[]
}>({
  biography: undefined,
  photos: [],
  tags: [],
})

onBeforeMount(async () => {
  if (!userInfoStore.user.username) {
    const res = await Api.get('/users/me').send()
    if (res.ok) userInfoStore.set((await res.json()) as User)
  }
  info.value.biography = userInfoStore.user.biography
  info.value.photos = []
  info.value.tags = userInfoStore.user.tags ?? []

  for (const image of userInfoStore.user.photos ?? []) {
    info.value.photos.push({ file: null, url: image })
  }
})

onUnmounted(async () => {
  const formData = new FormData()
  formData.append('biography', info.value.biography ?? '')

  info.value.photos.forEach((photo, index) => {
    formData.append(`photos[${index}][file]`, photo.file ?? '')
    formData.append(`photos[${index}][url]`, photo.url)
  })

  if (info.value.tags.length === 0) {
    formData.append('tags', '')
  } else {
    info.value.tags.forEach(tag => {
      formData.append('tags[]', tag)
    })
  }

  formData.append('_method', 'put')

  fetch(`https://${location.hostname}/api/users/me`, {
    method: 'POST',
    body: formData,
    headers: { Authorization: `Bearer ${localStorage.jwt}` },
  })

  Api.get('/users/me')
    .send()
    .then(res => res.json())
    .then((data: Partial<User>) => userInfoStore.add(data))
})
</script>

<template>
  <div class="flex flex-col gap-3">
    <div class="w-full flex justify-center">
      <ImageSelector
        v-model="info.photos"
        class="w-full max-w-sm"
      />
    </div>

    <div class="flex flex-col gap-3 bg-muted rounded-box p-3">
      <span>Biography :</span>
      <textarea
        v-model="info.biography"
        class="textarea textarea-bordered min-h-28"
        placeholder="Bio"
      ></textarea>
    </div>

    <div class="flex flex-col gap-3 bg-muted rounded-box p-3">
      <TagSelector v-model="info.tags" />
    </div>
  </div>
</template>

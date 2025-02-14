<script setup>
import ImageSelector from "@/components/userForm/ImageSelector.vue";
import TagSelector from "@/components/TagSelector.vue";
import {useUserInfoStore} from "@/store/userInfo.js";
import {Api} from "@/utils/api.js";
import {onBeforeMount, onUnmounted, ref} from "vue";

const userInfoStore = useUserInfoStore()
const info = ref({})

onBeforeMount(async () => {
  if (!userInfoStore.user.username)
  {
    const res = await Api.get('/users/me').send()
    if (res.ok)
      userInfoStore.set(await res.json())
  }
  info.value.biography = userInfoStore.user.biography
  info.value.photos = []
  info.value.tags = userInfoStore.user.tags

  for (const image of userInfoStore.user.photos) {
    info.value.photos.push({file: null, url: image})
  }
})

onUnmounted(async () => {
  Api.put('/users/me').send(info.value)
  Api.get('/users/me').send().then(res => res.json()).then(userInfoStore.add)
})
</script>

<template>
  <div class="flex flex-col gap-3">
    <div class="w-full flex justify-center">
      <image-selector v-model="info.photos" :model-value="info.photos" class="w-full max-w-sm" />
    </div>

    <div class="flex flex-col gap-3 bg-base-300 rounded-box p-3">
      <span>Biography :</span>
      <textarea
        v-model="info.biography"
        class="textarea textarea-bordered min-h-28"
        placeholder="Bio"
      ></textarea>
    </div>

    <div class="flex flex-col gap-3 bg-base-300 rounded-box p-3">
      <tag-selector v-model="info.tags" :model-value="info.tags" />
    </div>
  </div>
</template>

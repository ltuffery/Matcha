<script setup>
import ImageSelector from "@/components/userForm/ImageSelector.vue";
import TagSelector from "@/components/TagSelector.vue";
import {useUserInfoStore} from "@/store/userInfo.js";
import {Api} from "@/utils/api.js";
import {onMounted, ref} from "vue";

const userInfoStore = useUserInfoStore()
const info = ref({})

onMounted(async () => {
  if (!userInfoStore.user.username)
  {
    const res = await Api.get('/users/me').send()
    if (res.ok)
      userInfoStore.set(await res.json())
  }
  console.log(userInfoStore.user)
  info.value.bio = userInfoStore.user.biography
  info.value.photos = userInfoStore.user.photos
  info.value.tags = userInfoStore.user.tags
})
</script>

<template>
  <div>
    <div class="w-full flex justify-center">
      <image-selector class="w-full max-w-sm" />
    </div>

    <div class="flex flex-col gap-3 bg-base-300 rounded-box p-3">
      <span>Biography :</span>
      <textarea
        v-model="info.bio"
        class="textarea textarea-bordered min-h-28"
        placeholder="Bio"
      ></textarea>
    </div>

    <div class="flex flex-col gap-3 bg-base-300 rounded-box p-3">
      <tag-selector />
    </div>
  </div>
</template>

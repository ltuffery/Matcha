<script setup lang="ts">
import ProfileView from '@/components/ProfileView.vue'
import PreferencesSettings from '@/components/settings/PreferencesSettings.vue'
import AccountSettings from '@/components/settings/AccountSettings.vue'
import { ref } from 'vue'
import { Api } from '@/utils/api'
import LoadingScreen from '@/components/screen/LoadingScreen.vue'
import router from '@/router'
import { usePreferencesStore } from '@/store/preferences'
import { useUserInfoStore } from '@/store/userInfo'
import type { JwtPayload, User } from '@/types'

const loading = ref(true)
const settingsCategory = ref(1)
const profile = ref<Partial<User>>({})
const preferencesStore = usePreferencesStore()
const userInfoStore = useUserInfoStore()

Api.get('/users/me')
  .send()
  .then(res => res.json())
  .then((data: User & { preferences: User['preferences'] }) => {
    profile.value = data
    preferencesStore.setPreferences(data.preferences!)
    userInfoStore.set(data)
    loading.value = false
  })

const changeSettings = (e: Event) => {
  const target = e.target as HTMLElement
  if (target.id === 'ac') {
    settingsCategory.value = 2
    target.id = 'pr'
    target.innerHTML = 'Preferences Settings'
  } else {
    settingsCategory.value = 1
    target.id = 'ac'
    target.innerHTML = 'Account Settings'
  }
}

const goToProfile = () => {
  const decoded = JSON.parse(atob(localStorage.jwt!.split('.')[1])) as JwtPayload
  const username = decoded.username
  router.push({ name: 'profile', params: { username } })
}
</script>

<template>
  <LoadingScreen v-if="loading" />

  <div v-else class="flex w-full h-full flex-col gap-3">
    <div class="flex w-full items-center flex-col gap-6">
      <div class="pt-14">
        <ProfileView
          class="w-20 h-36 cursor-pointer"
          :images="profile.photos ?? []"
          @click="goToProfile"
        />
      </div>
      <div class="text-xl">{{ profile.first_name }}</div>
    </div>

    <div>
      <div class="card bg-muted gap-3 w-full p-5">
        <button @click="changeSettings" id="ac" class="btn">
          Account Settings
        </button>
      </div>
    </div>
    <PreferencesSettings v-if="settingsCategory === 1" />

    <AccountSettings :data="profile" v-else />
  </div>
</template>

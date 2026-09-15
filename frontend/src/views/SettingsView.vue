<script setup lang="ts">
import ProfileView from '@/components/ProfileView.vue'
import PreferencesSettings from '@/components/settings/PreferencesSettings.vue'
import AccountSettings from '@/components/settings/AccountSettings.vue'
import LoadingScreen from '@/components/screen/LoadingScreen.vue'
import { ref, onMounted } from 'vue'
import { Api } from '@/utils/api'
import router from '@/router'
import { usePreferencesStore } from '@/store/preferences'
import { useUserInfoStore } from '@/store/userInfo'
import type { JwtPayload, User } from '@/types'

import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs'

const loading = ref(true)
const profile = ref<Partial<User>>({})
const activeTab = ref<'preferences' | 'account'>('preferences')

const preferencesStore = usePreferencesStore()
const userInfoStore = useUserInfoStore()

const fetchProfile = async () => {
  try {
    const res = await Api.get('/users/me').send()
    const data: User & { preferences: User['preferences'] } = await res.json()

    profile.value = data
    preferencesStore.setPreferences(data.preferences!)
    userInfoStore.set(data)
  } finally {
    loading.value = false
  }
}

const goToProfile = () => {
  const decoded = JSON.parse(
    atob(localStorage.jwt!.split('.')[1]),
  ) as JwtPayload
  router.push({ name: 'profile', params: { username: decoded.username } })
}

onMounted(fetchProfile)
</script>

<template>
  <LoadingScreen v-if="loading" />

  <div v-else class="flex w-full h-full flex-col gap-6">
    <!-- Header profil -->
    <div class="flex w-full flex-col items-center gap-4 pt-14">
<!--      <ProfileView-->
<!--        class="w-20 h-36 cursor-pointer rounded-lg overflow-hidden"-->
<!--        :images="profile.photos ?? []"-->
<!--        @click="goToProfile"-->
<!--      />-->
<!--      <div class="text-xl font-semibold">{{ profile.first_name }}</div>-->
    </div>

    <!-- Onglets settings -->
    <Tabs v-model="activeTab" default-value="preferences" class="w-full flex-col">
      <TabsList class="grid w-full grid-cols-2">
        <TabsTrigger value="preferences">Préférences</TabsTrigger>
        <TabsTrigger value="account">Compte</TabsTrigger>
      </TabsList>

      <TabsContent value="preferences">
        <PreferencesSettings />
      </TabsContent>

      <TabsContent value="account">
        <AccountSettings :data="profile" />
      </TabsContent>
    </Tabs>
  </div>
</template>

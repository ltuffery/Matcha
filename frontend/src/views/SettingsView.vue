<script setup>
import ProfileView from '@/components/ProfileView.vue'
import PreferencesSettings from '@/components/settings/PreferencesSettings.vue'
import AccountSettings from '@/components/settings/AccountSettings.vue'
import { ref } from 'vue'
import { Api } from '@/utils/api.js'
import LoadingScreen from '@/components/screen/LoadingScreen.vue'

const loading = ref(true)
const settingsCategory = ref(1)
const profile = ref({})

Api.get('/users/me')
  .send()
  .then(res => res.json())
  .then(data => {
    profile.value = data
    loading.value = false
  })

const changeSettings = e => {
  if (e.target.id === 'ac') {
    settingsCategory.value = 2
    e.target.id = 'pr'
    e.target.innerHTML = 'Preferences Settings'
  } else {
    settingsCategory.value = 1
    e.target.id = 'ac'
    e.target.innerHTML = 'Account Settings'
  }
}
</script>

<template>
  <LoadingScreen v-if="loading" />

  <div v-else class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl px-6">
      <div class="flex w-full h-full flex-col gap-3">
        <div class="flex w-full items-center flex-col gap-6">
          <div class="pt-14">
            <ProfileView
              class="w-20 h-36 cursor-pointer"
              :images="profile.photos"
            />
          </div>
          <div class="text-xl">{{ profile.first_name }}</div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <button @click="changeSettings" id="ac" class="btn">
              Account Settings
            </button>
          </div>
        </div>
        <PreferencesSettings
          :preferences="profile.preferences"
          v-if="settingsCategory === 1"
        />

        <AccountSettings :data="profile" v-else />
      </div>
    </div>
  </div>
</template>

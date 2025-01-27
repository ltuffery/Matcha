<script setup>
import ProfileView from '@/components/ProfileView.vue'
import PreferencesSettings from '@/components/settings/PreferencesSettings.vue'
import AccountSettings from '@/components/settings/AccountSettings.vue'
import { ref } from 'vue'

const temporaryImage = [
  'https://as1.ftcdn.net/v2/jpg/06/12/64/52/1000_F_612645270_KBcBTf5CToMDyGr1hDpqSNyMK6eaXrPq.jpg',
  'http://as1.ftcdn.net/v2/jpg/09/25/29/78/1000_F_925297895_12R4VyhRAEmvMGHEdGSXXj3B9PTHVYUa.jpg',
  'https://as1.ftcdn.net/v2/jpg/02/44/38/08/1000_F_244380850_H3xd2rrb9CfCIcTyFcepVL670vvuTA0b.jpg',
]

let settingsCategory = ref(1)

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
  <div class="flex bg-base-300 h-dvh w-full justify-center items-center">
    <div class="overflow-y-auto bg-base-200 h-full w-full max-w-3xl px-6">
      <div class="flex w-full h-full flex-col gap-3">
        <div class="flex w-full items-center flex-col gap-6">
          <div class="pt-14">
            <ProfileView
              class="w-20 h-36 cursor-pointer"
              :images="temporaryImage"
            />
          </div>
          <div class="text-xl">First Name</div>
        </div>

        <div>
          <div class="card bg-base-300 gap-3 w-full p-5">
            <button @click="changeSettings" id="ac" class="btn">
              Account Settings
            </button>
          </div>
        </div>
        <PreferencesSettings v-if="settingsCategory === 1" />

        <AccountSettings v-else />
      </div>
    </div>
  </div>
</template>

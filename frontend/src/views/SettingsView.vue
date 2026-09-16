<script setup lang="ts">
import { ref } from 'vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import {
  User,
  Shield,
  Heart,
  CreditCard,
  AlertTriangle,
  Lock,
} from 'lucide-vue-next'
import AccountSection from '@/components/settings/sections/AccountSection.vue'
import ProfileSection from '@/components/settings/sections/ProfileSection.vue'
import PrivacySection from '@/components/settings/sections/PrivacySection.vue'
import MatchingPreferencesSection from '@/components/settings/sections/MatchingPreferencesSection.vue'
import SubscriptionSection from '@/components/settings/sections/SubscriptionSection.vue'
import DangerAreaSection from '@/components/settings/sections/DangerAreaSection.vue'

const activeTab = ref('profile')

const navItems = [
  { value: 'profile', label: 'Profile', icon: User },
  { value: 'account', label: 'Account', icon: Lock },
  { value: 'privacy', label: 'Privacy', icon: Shield },
  { value: 'matching', label: 'Preferences', icon: Heart },
  { value: 'billing', label: 'Subscription', icon: CreditCard },
  { value: 'danger', label: 'Danger area', icon: AlertTriangle },
]
</script>

<template>
  <div class="min-h-screen">
    <div class="mx-auto max-w-6xl px-4 py-10">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
        <p class="text-muted-foreground mt-1">
          Manage your profile, privacy settings, and preferences.
        </p>
      </div>

      <Tabs
        v-model="activeTab"
        orientation="vertical"
        class="flex flex-col md:flex-row gap-8"
      >
        <!-- Sidebar nav -->
        <TabsList
          class="flex md:flex-col h-auto bg-transparent p-0 gap-1 md:w-64 shrink-0"
        >
          <TabsTrigger
            v-for="item in navItems"
            :key="item.value"
            :value="item.value"
            class="w-full justify-start gap-2 px-3 py-2.5 data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-lg font-medium"
          >
            <component :is="item.icon" class="h-4 w-4" />
            {{ item.label }}
          </TabsTrigger>
        </TabsList>

        <!-- Content -->
        <div class="flex-1 space-y-6">
          <!-- PROFILE -->
          <TabsContent value="profile" class="mt-0 space-y-6">
            <ProfileSection />
          </TabsContent>

          <!-- ACCOUNT -->
          <TabsContent value="account" class="mt-0 space-y-6">
            <AccountSection />
          </TabsContent>

          <!-- PRIVACY -->
          <TabsContent value="privacy" class="mt-0 space-y-6">
            <PrivacySection />
          </TabsContent>

          <!-- MATCHING PREFERENCES -->
          <TabsContent value="matching" class="mt-0 space-y-6">
            <MatchingPreferencesSection />
          </TabsContent>

          <!-- SUBSCRIPTION -->
          <TabsContent value="billing" class="mt-0 space-y-6">
            <SubscriptionSection />
          </TabsContent>

          <!-- DANGER AREA -->
          <TabsContent value="danger" class="mt-0 space-y-6">
            <DangerAreaSection />
          </TabsContent>
        </div>
      </Tabs>
    </div>
  </div>
</template>

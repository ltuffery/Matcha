<template>
  <div class="relative isolate flex min-h-screen w-full overflow-hidden">
    <!-- Fond (visible tant qu'on est sous xl) -->
    <Marquee3D
      class="pointer-events-none absolute inset-0 -z-10 h-full w-full object-cover opacity-30 xl:hidden"
      :images="images"
    />

    <!-- Formulaire -->
    <div
      class="flex w-full items-center justify-center px-8 xl:w-1/3 xl:bg-background"
    >
      <div class="w-full max-w-lg">
        <Card
          class="border-0 bg-background/80 shadow-none backdrop-blur-md xl:bg-transparent xl:backdrop-blur-none"
        >
          <Tabs default-value="login" class="w-full">
            <CardHeader>
              <TabsList class="w-full">
                <TabsTrigger value="login">Connexion</TabsTrigger>
                <TabsTrigger value="register">Inscription</TabsTrigger>
              </TabsList>
            </CardHeader>

            <CardContent>
              <TabsContent value="login">
                <LoginForm />
              </TabsContent>

              <TabsContent value="register">
                <RegisterFrom />
              </TabsContent>
            </CardContent>
          </Tabs>
        </Card>
      </div>
    </div>

    <!-- Desktop : colonne de droite -->
    <div class="relative hidden w-2/3 xl:block">
      <Marquee3D class="h-full w-full object-cover" :images="images" />
    </div>
  </div>
</template>

<script setup lang="ts">
import RegisterFrom from '@/components/auth/RegisterForm.vue'
import LoginForm from '@/components/auth/LoginForm.vue'
import { TabsContent, TabsList, TabsTrigger, Tabs } from '@/components/ui/tabs'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Marquee3D } from '@/components/ui/marquee-3d'

const images = [
  'https://images.unsplash.com/photo-1699746277651-3b1438122eaa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1622236297949-eb7f7aa3a3cc?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1662933170614-c713579f00c2?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1610555423081-85ec0b8eabac?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1592250590197-68d65ad73ecf?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1643932919088-53349a7c3385?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1643904524951-2a3a58856745?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1545209519-900ef21c1c86?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1548980989-c2c780b8df5d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1531764117131-cbd26e7885f0?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1589155364518-6be9060bd86b?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1590794249657-4ef14206142e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1557053910-d9eadeed1c58?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1609786280864-1c6c5344ed46?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1604653316184-a066e0552c64?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
  'https://images.unsplash.com/photo-1543096222-72de739f7917?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=NHwxMjA3fDA%3D',
]
</script>

<template>
  <main
    class="grid grid-cols-1 place-content-center h-dvh place-items-center bg-muted px-2"
  >
    <Card>
      <Tabs defaultValue="login" className="w-full">
        <CardHeader>
          <TabsList class="w-full">
            <TabsTrigger value="login">Login</TabsTrigger>
            <TabsTrigger value="register">Register</TabsTrigger>
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
  </main>
</template>

<script setup lang="ts">
import RegisterFrom from '@/components/auth/RegisterForm.vue'
import LoginForm from '@/components/auth/LoginForm.vue'
import { TabsContent, TabsList, TabsTrigger, Tabs } from '@/components/ui/tabs'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
</script>

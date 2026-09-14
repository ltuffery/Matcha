<template>
  <div class="flex min-h-screen w-full">
    <div
      class="flex w-full items-center justify-center lg:w-1/3 bg-background px-8"
    >
      <div class="w-full max-w-lg">
        <Card class="border-0 shadow-none">
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
      </div>
    </div>

    <div class="relative hidden w-2/3 lg:block">
      <img
        src="https://placehold.co/600x400"
        alt="Login background"
        class="h-full w-full object-cover"
      />
    </div>
  </div>
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
import DateOfBirthPicker from '@/components/forms/DateOfBirthPicker.vue'
</script>

<script setup lang="ts">
import { h, ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import { Field as VeeField, Form as VeeForm } from 'vee-validate'
import * as z from 'zod'

import router from '@/router'
import { login } from '@/services/auth'
import { Api } from '@/utils/api'
import { connectSocket } from '@/plugins/socket'

import FeedbackToast from '@/components/FeedbackToast.vue'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Field, FieldError, FieldLabel } from '@/components/ui/field'

const forgotPwd = ref(false)
const toastsRef = ref<InstanceType<typeof FeedbackToast>>()

const loginSchema = toTypedSchema(
  z.object({
    username: z
      .string()
      .trim()
      .min(1, 'Username is required')
      .min(3, 'Username must contain at least 3 characters'),

    password: z
      .string()
      .min(1, 'Password is required')
      .min(8, 'Password must contain at least 8 characters'),
  }),
)

const forgotSchema = toTypedSchema(
  z.object({
    email: z
      .string()
      .trim()
      .min(1, 'Email is required')
      .email('Please enter a valid email'),
  }),
)

function forgotSwitch() {
  forgotPwd.value = !forgotPwd.value
}

async function forgetPassword(values: { email: string }) {
  try {
    const response = await Api.post('forgot/credencial').send({
      email: values.email,
    })

    if (response.status === 200) {
      toastsRef.value?.addSuccess('Email sent!')
    } else {
      toastsRef.value?.addError('Error!')
    }
  } catch {
    toastsRef.value?.addError('An unexpected error occurred')
  }
}

async function loginUserAccount(values: {
  username: string
  password: string
}) {
  const res = await login(values.username, values.password)

  if (res == null) return

  if (!(res instanceof Response)) {
    navigator.geolocation.getCurrentPosition(
      loc => {
        Api.put('/users/me/localisation').send({
          lat: loc.coords.latitude,
          lon: loc.coords.longitude,
        })
      },
      () => {
        Api.put('/users/me/localisation').send()
      },
    )

    connectSocket()
    router.push({ name: 'browse' })
    return
  }

  switch (res.status) {
    case 400:
      toastsRef.value?.addError('Bad credentials')
      break
    case 401:
      toastsRef.value?.addWarning("Your email isn't verified")
      break
    default:
      toastsRef.value?.addError('Unexpected error occurred')
  }
}
</script>

<template>
  <FeedbackToast ref="toastsRef" posX="end" class="h-2/6" />

  <!-- Mot de passe oublié -->
  <VeeForm
    v-if="forgotPwd"
    v-slot="{ isSubmitting }"
    :validation-schema="forgotSchema"
    @submit="forgetPassword"
  >
    <h3 class="my-5 text-3xl font-bold">Forgot credential</h3>

    <p>
      Forgot your username or password?
      <br />
      Receive and change it by mail:
    </p>

    <VeeField v-slot="{ componentField, errors }" name="email">
      <Field class="mt-3" :data-invalid="!!errors.length">
        <FieldLabel for="forgot-email"> Email </FieldLabel>

        <Input
          id="forgot-email"
          v-bind="componentField"
          type="email"
          placeholder="Email"
          :aria-invalid="!!errors.length"
        />

        <FieldError v-if="errors.length" :errors="errors" />
      </Field>
    </VeeField>

    <div class="mt-6 flex justify-between">
      <Button type="button" class="w-2/6" @click="forgotSwitch">
        Go back
      </Button>

      <Button
        type="submit"
        variant="outline"
        class="ml-2 w-2/6"
        :disabled="isSubmitting"
      >
        Send
      </Button>
    </div>
  </VeeForm>

  <!-- Connection -->
  <VeeForm
    v-else
    class="flex flex-col gap-3"
    :validation-schema="loginSchema"
    @submit="loginUserAccount"
  >
    <h3 class="my-5 text-3xl font-bold">Login!</h3>

    <VeeField v-slot="{ componentField, errors }" name="username">
      <Field :data-invalid="!!errors.length">
        <FieldLabel for="username"> Username </FieldLabel>

        <Input
          id="username"
          v-bind="componentField"
          type="text"
          placeholder="Username"
          :aria-invalid="!!errors.length"
        />

        <FieldError v-if="errors.length" :errors="errors" />
      </Field>
    </VeeField>

    <VeeField v-slot="{ componentField, errors }" name="password">
      <Field :data-invalid="!!errors.length">
        <FieldLabel for="password"> Password </FieldLabel>

        <Input
          id="password"
          v-bind="componentField"
          type="password"
          placeholder="Password"
          :aria-invalid="!!errors.length"
        />

        <FieldError v-if="errors.length" :errors="errors" />
      </Field>
    </VeeField>

    <div class="mt-3 flex w-full justify-end">
      <button
        type="button"
        class="cursor-pointer hover:underline"
        @click="forgotSwitch"
      >
        Forgot credential?
      </button>
    </div>

    <Button type="submit" class="mt-6 w-full"> Login </Button>
  </VeeForm>
</template>

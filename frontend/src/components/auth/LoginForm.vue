<script setup lang="ts">
import { ref } from 'vue'
import { toTypedSchema } from '@vee-validate/zod'
import {
  Field as VeeField,
  Form as VeeForm,
  type GenericObject,
} from 'vee-validate'
import * as z from 'zod'

import router from '@/router'

import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Field, FieldError, FieldLabel } from '@/components/ui/field'
import { useAuth } from '@/composable/useAuth'
import { toast } from 'vue-sonner'
import Password from '@/components/inputs/Password.vue'
import { connectSocket } from '@/plugins/socket'

const auth = useAuth()

const forgotPwd = ref(false)

const loginSchema = toTypedSchema(
  z.object({
    username: z.string({ required_error: 'Requis' }).trim().min(1, 'Requis'),

    password: z.string({ required_error: 'Requis' }).min(1, 'Requis'),
  }),
)

const forgotSchema = toTypedSchema(
  z.object({
    email: z
      .string({ required_error: 'Requis' })
      .trim()
      .min(1, 'Requis')
      .email('Veuillez saisir une adresse e-mail valide'),
  }),
)

function forgotSwitch() {
  forgotPwd.value = !forgotPwd.value
}

async function forgetPassword(values: GenericObject) {
  const email = values.email as string

  try {
    const isSend = await auth.forgotPassword(email)

    if (isSend) {
      toast.success('Email envoyé !')
    } else {
      toast.error('Erreur !')
    }
  } catch {
    toast.error('Une erreur inattendue s’est produite')
  }
}

async function loginUserAccount(values: GenericObject) {
  const username = values.username as string
  const password = values.password as string
  const res = await auth.login(username, password)
  if (!res.success) {
    toast.error(res.error as string)
  } else {
    toast.success(`Bienvenu ${username}`)
    try {
      await connectSocket()
    } catch {}
    await router.push({ name: 'home' })
  }
}
</script>

<template>
  <!-- Mot de passe oublié -->
  <VeeForm
    v-if="forgotPwd"
    v-slot="{ isSubmitting }"
    :validation-schema="forgotSchema"
    @submit="values => forgetPassword(values)"
  >
    <h3 class="my-5 text-3xl font-bold">Forgot credential</h3>

    <p>
      Vous avez oublié votre nom d’utilisateur ou votre mot de passe ?
      <br />
      Recevez-le et modifiez-le par email
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
        Retour
      </Button>

      <Button
        type="submit"
        variant="outline"
        class="ml-2 w-2/6"
        :disabled="isSubmitting"
      >
        envoyer
      </Button>
    </div>
  </VeeForm>

  <!-- Connection -->
  <VeeForm
    v-else
    class="flex flex-col gap-3"
    :validation-schema="loginSchema"
    @submit="values => loginUserAccount(values)"
  >
    <h3 class="my-5 text-3xl font-bold">Connectez-vous !</h3>

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

        <FieldError class="text-right" v-if="errors.length" :errors="errors" />
      </Field>
    </VeeField>

    <VeeField v-slot="{ componentField, errors }" name="password">
      <Field :data-invalid="!!errors.length">
        <FieldLabel for="password"> Password </FieldLabel>

        <Password v-bind="componentField" :invalid="!!errors.length" />

        <FieldError class="text-right" v-if="errors.length" :errors="errors" />
      </Field>
    </VeeField>

    <div class="mt-3 flex w-full justify-end">
      <button
        type="button"
        class="cursor-pointer hover:underline"
        @click="forgotSwitch"
      >
        Identifiants oubliés ?
      </button>
    </div>

    <Button type="submit" class="mt-6 w-full"> se connecter </Button>
  </VeeForm>
</template>

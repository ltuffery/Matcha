<script setup lang="ts">
import { ref, watch } from 'vue'
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { toast } from 'vue-sonner'
import { storeToRefs } from 'pinia'

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Separator } from '@/components/ui/separator'
import {
  Field,
  FieldDescription,
  FieldError,
  FieldGroup,
  FieldLabel,
} from '@/components/ui/field'
import { useAuthStore } from '@/store/useAuthStore'

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const schema = toTypedSchema(
  z
    .object({
      email: z.string().min(1, 'Email is required').email('Invalid email'),
      password: z
        .string()
        .min(8, 'Password must be at least 8 characters')
        .optional()
        .or(z.literal('')),
      passwordConfirm: z.string().optional().or(z.literal('')),
    })
    .refine(d => !d.password || d.password === d.passwordConfirm, {
      message: 'Passwords do not match',
      path: ['passwordConfirm'],
    }),
)

const {
  handleSubmit,
  isSubmitting,
  errors,
  setErrors,
  resetForm,
  defineField,
} = useForm({
  validationSchema: schema,
  // Le user est déjà dans Pinia (navigation sans F5)
  initialValues: {
    email: user.value?.email ?? '',
    password: '',
    passwordConfirm: '',
  },
})

const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [passwordConfirm, passwordConfirmAttrs] = defineField('passwordConfirm')

// Le user arrive plus tard (F5) ou change (après une sauvegarde)
watch(user, u => {
  if (u) {
    resetForm({
      values: { email: u.email ?? '', password: '', passwordConfirm: '' },
    })
  }
})

const serverError = ref<string | null>(null)

const onSubmit = handleSubmit(async values => {
  serverError.value = null
  try {
    await authStore.updateProfile({
      email: values.email,
      ...(values.password ? { password: values.password } : {}),
    })

    // Vide les champs mot de passe en gardant l'email actuel
    resetForm({
      values: { email: values.email, password: '', passwordConfirm: '' },
    })
    toast.success('Profile updated successfully')
  } catch (e: any) {
    const apiErrors = e?.response?.data?.errors
    if (apiErrors) setErrors(apiErrors)
    else serverError.value = e?.response?.data?.message ?? 'An error occurred'
    toast.error('Update failed')
  }
})
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Credentials</CardTitle>
      <CardDescription>Manage your email and password.</CardDescription>
    </CardHeader>

    <CardContent>
      <form class="space-y-4" @submit="onSubmit">
        <FieldGroup>
          <!-- Email -->
          <Field :data-invalid="!!errors.email">
            <FieldLabel for="email">Email</FieldLabel>
            <Input
              id="email"
              v-model="email"
              v-bind="emailAttrs"
              type="email"
              placeholder="your@email.com"
              autocomplete="email"
              :aria-invalid="!!errors.email"
            />
            <FieldError v-if="errors.email" :errors="[errors.email]" />
          </Field>

          <Separator />

          <!-- Nouveau mot de passe -->
          <Field :data-invalid="!!errors.password">
            <FieldLabel for="password">New password</FieldLabel>
            <Input
              id="password"
              v-model="password"
              v-bind="passwordAttrs"
              type="password"
              placeholder="********"
              autocomplete="new-password"
              :aria-invalid="!!errors.password"
            />
            <FieldDescription>
              Leave empty to keep your current password.
            </FieldDescription>
            <FieldError v-if="errors.password" :errors="[errors.password]" />
          </Field>

          <!-- Confirmation -->
          <Field :data-invalid="!!errors.passwordConfirm">
            <FieldLabel for="passwordConfirm">Confirm password</FieldLabel>
            <Input
              id="passwordConfirm"
              v-model="passwordConfirm"
              v-bind="passwordConfirmAttrs"
              type="password"
              placeholder="********"
              autocomplete="new-password"
              :aria-invalid="!!errors.passwordConfirm"
            />
            <FieldError
              v-if="errors.passwordConfirm"
              :errors="[errors.passwordConfirm]"
            />
          </Field>

          <p v-if="serverError" class="text-sm text-destructive">
            {{ serverError }}
          </p>

          <div class="flex justify-end">
            <Button type="submit" :disabled="isSubmitting">
              {{ isSubmitting ? 'Saving...' : 'Save changes' }}
            </Button>
          </div>
        </FieldGroup>
      </form>
    </CardContent>
  </Card>
</template>

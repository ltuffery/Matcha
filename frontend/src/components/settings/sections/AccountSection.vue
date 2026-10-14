<script setup lang="ts">
import { useForm, Field as VeeField } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { ref } from 'vue'
import { toast } from 'vue-sonner'

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
    .refine(d => !d.password || !d.passwordConfirm || d.password === d.passwordConfirm, {
      message: 'Passwords do not match',
      path: ['passwordConfirm'],
    }),
)

const { handleSubmit, isSubmitting, resetField, setErrors } = useForm({
  validationSchema: schema,
  initialValues: {
    email: authStore.user?.email ?? '',
    password: '',
    passwordConfirm: '',
  },
})

const serverError = ref<string | null>(null)

const onSubmit = handleSubmit(async values => {
  serverError.value = null
  try {
    await authStore.updateProfile({
      email: values.email,
      ...(values.password ? { password: values.password } : {}),
    })

    resetField('password')
    resetField('passwordConfirm')
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
          <VeeField v-slot="{ field, errors }" name="email">
            <Field :data-invalid="!!errors.length">
              <FieldLabel for="email">Email</FieldLabel>
              <Input
                id="email"
                type="email"
                placeholder="your@email.com"
                v-bind="field"
                :aria-invalid="!!errors.length"
              />
              <FieldError v-if="errors.length" :errors="errors" />
            </Field>
          </VeeField>

          <Separator />

          <VeeField v-slot="{ field, errors }" name="password">
            <Field :data-invalid="!!errors.length">
              <FieldLabel for="password">New password</FieldLabel>
              <Input
                id="password"
                type="password"
                placeholder="********"
                v-bind="field"
                :aria-invalid="!!errors.length"
              />
              <FieldDescription>
                Leave empty to keep your current password.
              </FieldDescription>
              <FieldError v-if="errors.length" :errors="errors" />
            </Field>
          </VeeField>

          <VeeField v-slot="{ field, errors }" name="passwordConfirm">
            <Field :data-invalid="!!errors.length">
              <FieldLabel for="passwordConfirm">Confirm password</FieldLabel>
              <Input
                id="passwordConfirm"
                type="password"
                placeholder="********"
                v-bind="field"
                :aria-invalid="!!errors.length"
              />
              <FieldError v-if="errors.length" :errors="errors" />
            </Field>
          </VeeField>

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

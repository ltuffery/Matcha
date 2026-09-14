<script setup lang="ts">
import { Check, Circle, Dot, Plus, X } from 'lucide-vue-next'
import { toTypedSchema } from '@vee-validate/zod'
import { Field as VeeField, Form as VeeForm } from 'vee-validate'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import * as z from 'zod'

import { Button } from '@/components/ui/button'
import {
  Field,
  FieldError,
  FieldGroup,
  FieldLabel,
} from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import {
  Stepper,
  StepperDescription,
  StepperItem,
  StepperSeparator,
  StepperTitle,
  StepperTrigger,
} from '@/components/ui/stepper'
import DateOfBirthPicker from '@/components/forms/DateOfBirthPicker.vue'

interface ImageItem {
  file: File
  url: string
}

const stepIndex = ref(1)
const inputImage = ref<HTMLInputElement | null>(null)
const images = ref<ImageItem[]>([])

const formSchema = [
  z.object({
    username: z.string().min(2, 'Username requis'),
    email: z.string().email('Email invalide'),
    password: z.string().min(8, 'Minimum 8 caractères'),
  }),
  z.object({
    first_name: z.string().min(2, 'Prénom requis'),
    last_name: z.string().min(2, 'Nom requis'),
    birthday: z.string().min(1, 'Date de naissance requise'),
  }),
  z.object({
    gender: z.enum(['M', 'F', 'O'], {
      message: 'Sélectionne un genre',
    }),
  }),
  z.object({
    biography: z.string().max(500).optional(),
    tags: z.array(z.string()).optional(),
  }),
  z.object({
    photos: z.custom(
      () => images.value.length > 0,
      'Ajoute au moins une photo',
    ),
  }),
]

const steps = [
  {
    step: 1,
    title: 'Account',
    description: 'Your account information',
  },
  {
    step: 2,
    title: 'Identity',
    description: 'Your personals information',
  },
  {
    step: 3,
    title: 'Profile',
    description: 'Profile personalisation',
  },
  {
    step: 4,
    title: 'Photos',
    description: 'Add your photos',
  },
]

const maxAge = new Date()
maxAge.setFullYear(maxAge.getFullYear() - 18)
const maxBirthday = maxAge.toISOString().split('T')[0]

function handleFileUpload(event: Event) {
  const target = event.target as HTMLInputElement

  if (!target.files) return

  for (const file of Array.from(target.files)) {
    if (images.value.length >= 5) break

    images.value.push({
      file,
      url: URL.createObjectURL(file),
    })
  }

  target.value = ''
}

function removeImage(index: number) {
  URL.revokeObjectURL(images.value[index].url)
  images.value.splice(index, 1)
}

async function handleSubmit(values: Record<string, unknown>) {
  try {
    const form = new FormData()

    Object.entries(values).forEach(([key, value]) => {
      if (key !== 'photos' && value !== undefined) {
        form.append(
          key,
          Array.isArray(value) ? JSON.stringify(value) : String(value),
        )
      }
    })

    images.value.forEach(image => {
      form.append('photos[]', image.file, image.file.name)
    })

    const response = await fetch(
      `https://${location.hostname}/api/auth/register`,
      {
        method: 'POST',
        body: form,
      },
    )

    const data = await response.json()

    if (!response.ok) {
      toast.error(data.message ?? 'Une erreur est survenue')
      return
    }

    toast.success('Compte créé avec succès')
    stepIndex.value = steps.length
  } catch (error) {
    console.error(error)
    toast.error('Erreur lors de la création du compte')
  }
}
</script>

<template>
  <VeeForm
    v-slot="{ meta, values, validate }"
    as=""
    keep-values
    :validation-schema="toTypedSchema(formSchema[stepIndex - 1]!)"
  >
    <Stepper
      v-slot="{
        isNextDisabled,
        isPrevDisabled,
        nextStep,
        prevStep,
        modelValue,
      }"
      v-model="stepIndex"
      class="mx-auto w-full max-w-4xl mt-6"
    >
      <form
        @submit="
          async event => {
            event.preventDefault()
            const result = await validate()

            if (!result.valid) return

            if (stepIndex === steps.length) {
              await handleSubmit(values)
            } else {
              nextStep()
            }
          }
        "
      >
        <!-- Navigation des étapes -->
        <div class="flex w-full gap-2">
          <StepperItem
            v-for="(step, index) in steps"
            :key="step.step"
            v-slot="{ state }"
            :step="step.step"
            class="relative flex w-full flex-col items-center"
          >
            <StepperSeparator
              v-if="step.step !== steps[steps.length - 1].step"
              class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 h-0.5 bg-muted"
            />

            <StepperTrigger as-child>
              <Button
                :variant="
                  state === 'completed' || state === 'active'
                    ? 'default'
                    : 'outline'
                "
                size="icon"
                class="z-10 rounded-full"
                :class="
                  state === 'active'
                    ? 'ring-2 ring-ring ring-offset-2 ring-offset-background'
                    : ''
                "
                :disabled="
                  state !== 'completed' &&
                  index >= (modelValue || 0) &&
                  !meta.valid
                "
              >
                <Check v-if="state === 'completed'" class="size-5" />
                <Circle v-else-if="state === 'active'" />
                <Dot v-else />
              </Button>
            </StepperTrigger>

            <div class="mt-4 text-center">
              <StepperTitle
                class="text-sm font-semibold"
                :class="state === 'active' ? 'text-primary' : ''"
              >
                {{ step.title }}
              </StepperTitle>
              <StepperDescription
                class="hidden text-xs text-muted-foreground md:block"
              >
                {{ step.description }}
              </StepperDescription>
            </div>
          </StepperItem>
        </div>

        <FieldGroup class="mt-6">
          <!-- Step 1 : account -->
          <template v-if="stepIndex === 1">
            <VeeField v-slot="{ componentField, errors }" name="username">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Username</FieldLabel>
                <Input
                  v-bind="componentField"
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="email">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Email</FieldLabel>
                <Input
                  v-bind="componentField"
                  type="email"
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="password">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Password</FieldLabel>
                <Input
                  v-bind="componentField"
                  type="password"
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>
          </template>

          <!-- Step 2 : identity -->
          <template v-else-if="stepIndex === 2">
            <VeeField v-slot="{ componentField, errors }" name="first_name">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Prénom</FieldLabel>
                <Input
                  v-bind="componentField"
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="last_name">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Nom</FieldLabel>
                <Input
                  v-bind="componentField"
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ value, handleChange, errors }" name="birthday">
              <Field :data-invalid="!!errors.length">
                <DateOfBirthPicker
                  :model-value="value"
                  :error="!!errors.length"
                  @update:model-value="handleChange"
                  class="w-full"
                />

                <FieldError :errors="errors" />
              </Field>
            </VeeField>
          </template>

          <!-- Step 3 : profile -->
          <template v-else-if="stepIndex === 3">
            <VeeField v-slot="{ componentField, errors }" name="gender">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Genre</FieldLabel>

                <Select v-bind="componentField">
                  <SelectTrigger :aria-invalid="!!errors.length">
                    <SelectValue placeholder="Choisir une option" />
                  </SelectTrigger>

                  <SelectContent>
                    <SelectItem value="M">Homme</SelectItem>
                    <SelectItem value="F">Femme</SelectItem>
                    <SelectItem value="O">Autre</SelectItem>
                  </SelectContent>
                </Select>

                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="biography">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Biographie</FieldLabel>
                <Textarea
                  v-bind="componentField"
                  placeholder="Présente-toi..."
                  :aria-invalid="!!errors.length"
                />
                <FieldError :errors="errors" />
              </Field>
            </VeeField>

            <!--            TODO: Add Tag selector -->
          </template>

          <!-- Step 5 : photos -->
          <template v-else-if="stepIndex === 4">
            <Field>
              <FieldLabel> Photos — {{ images.length }}/5 </FieldLabel>

              <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                <button
                  v-if="images.length < 5"
                  type="button"
                  class="flex h-40 items-center justify-center rounded-md border-2 border-dashed border-primary text-primary transition hover:bg-primary/10"
                  @click="inputImage?.click()"
                >
                  <Plus class="size-9" />
                </button>

                <div
                  v-for="(image, index) in images"
                  :key="image.url"
                  class="group relative h-40 overflow-hidden rounded-md border"
                >
                  <img
                    :src="image.url"
                    alt="Selected photo"
                    class="h-full w-full object-cover"
                  />

                  <Button
                    type="button"
                    size="icon"
                    variant="destructive"
                    class="absolute right-2 top-2 opacity-0 transition group-hover:opacity-100"
                    @click="removeImage(index)"
                  >
                    <X class="size-4" />
                  </Button>
                </div>
              </div>

              <input
                ref="inputImage"
                type="file"
                accept="image/*"
                multiple
                class="hidden"
                @change="handleFileUpload"
              />
            </Field>
          </template>
        </FieldGroup>

        <!-- Boutons de navigation -->
        <div class="mt-8 flex justify-between">
          <Button
            type="button"
            variant="outline"
            :disabled="isPrevDisabled"
            @click="prevStep()"
          >
            Previous
          </Button>

          <Button
            type="submit"
            :disabled="isNextDisabled && stepIndex < steps.length"
          >
            {{ stepIndex === steps.length ? 'Create my account' : 'Next' }}
          </Button>
        </div>
      </form>
    </Stepper>
  </VeeForm>
</template>

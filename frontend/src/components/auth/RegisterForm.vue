<script setup lang="ts">
import { Check, Circle, Dot, Plus, X } from 'lucide-vue-next'
import { toTypedSchema } from '@vee-validate/zod'
import { Field as VeeField, Form as VeeForm } from 'vee-validate'
import { onBeforeUnmount, ref } from 'vue'
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
import { useAuth } from '@/composable/useAuth'
import { useMail } from '@/composable/useMail'
import { TagsInput, TagsInputInput } from '@/components/ui/tags-input'
import {
  Card,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import Password from '@/components/inputs/Password.vue'
import { Spinner } from '@/components/ui/spinner'
import type { VerifyMailResponse } from '@/api/email'

interface ImageItem {
  file: File
  url: string
}

const auth = useAuth()
const mail = useMail()

const isDone = ref(false)
const processing = ref(false)
const stepIndex = ref(1)
const inputImage = ref<HTMLInputElement | null>(null)
const images = ref<ImageItem[]>([])
const registeredEmail = ref('')

const RESEND_COOLDOWN = 60
const cooldown = ref(0)
let timer: ReturnType<typeof setInterval> | undefined

const formSchema = [
  z.object({
    username: z
      .string({ required_error: 'Requis' })
      .trim()
      .min(1, 'Requis')
      .min(5, 'Au moins 5 caractères')
      .max(25, 'Au maximum 25 caractères')
      .regex(
        /^[a-zA-Z0-9.]+$/,
        'Seuls les lettres, les chiffres et les points sont autorisés',
      ),
    email: z
      .string({ required_error: 'Requis' })
      .min(1, 'Requis')
      .email('Email invalide'),
    password: z
      .string({ required_error: 'Requis' })
      .min(1, 'Requis')
      .min(8, 'Au moins 8 caractères')
      .max(128, 'Au maximum 128 caractères')
      .regex(/[a-z]/, 'Ajoutez une lettre minuscule')
      .regex(/[A-Z]/, 'Ajoutez une majuscule')
      .regex(/[0-9]/, 'Ajouter un nombre'),
  }),
  z.object({
    first_name: z.string({ required_error: 'Requis' }).min(1, 'requis'),
    last_name: z.string({ required_error: 'Requis' }).min(1, 'requis'),
    birthday: z
      .string({ required_error: 'Date de naissance requise' })
      .min(1, 'Date de naissance requise')
      .refine(v => v <= getMaxBirthday(), 'Vous devez avoir au moins 18 ans'),
  }),
  z.object({
    gender: z.enum(['M', 'F', 'O'], {
      message: 'Sélectionne un genre',
    }),
    biography: z
      .string({ required_error: 'Requis' })
      .max(142, 'La chaîne doit contenir au maximum 142 caractères'),
    tags: z.array(z.string()).optional(),
  }),
  z.object({
    photos: z.custom(
      () => images.value.length > 0,
      'Ajoutez au moins une photo',
    ),
  }),
]

const steps = [
  {
    step: 1,
    title: 'Compte',
    description: 'Les informations de votre compte',
  },
  {
    step: 2,
    title: 'Identité',
    description: 'Vos informations personnelles',
  },
  {
    step: 3,
    title: 'Profil',
    description: 'Personnalisation du profil',
  },
  {
    step: 4,
    title: 'Photos',
    description: 'Ajoutez vos photos',
  },
]

function startCooldown(seconds = RESEND_COOLDOWN) {
  clearInterval(timer)
  cooldown.value = seconds
  timer = setInterval(() => {
    cooldown.value--
    if (cooldown.value <= 0) clearInterval(timer)
  }, 1000)
}

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
  processing.value = true
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

    await auth.register(form)
    processing.value = false

    if (auth.error.value) {
      toast.error(auth.error.value ?? 'Une erreur est survenue')
      return
    }

    registeredEmail.value = String(values.email)
    isDone.value = true
  } catch (error) {
    const message =
      error?.response?.data?.error ??
      error?.message ??
      'Erreur lors de la création du compte'
    toast.error(String(message))
    if (String(message).toLowerCase().includes('password')) {
      stepIndex.value = 1
    } else if (String(message).toLowerCase().includes('username')) {
      stepIndex.value = 1
    } else if (String(message).toLowerCase().includes('email')) {
      stepIndex.value = 1
    }
    processing.value = false
  }
}

function getMaxBirthday() {
  const d = new Date()
  d.setFullYear(d.getFullYear() - 18)
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`
}

const resendMail = async (email: string) => {
  if (cooldown.value > 0) return
  startCooldown()
  const res : VerifyMailResponse = await mail.verify(email)
  if (!res.success){
    toast.error(res.error as string)
  }
}
onBeforeUnmount(() => clearInterval(timer))
</script>

<template>
  <Card v-if="isDone" class="mx-auto mt-12 w-full max-w-md text-center">
    <CardHeader>
      <CardTitle>Compte créé 🎉</CardTitle>
      <CardDescription>
        Un email de confirmation a été envoyé à
        <span class="font-medium text-foreground">{{ registeredEmail }}</span
        >. Valide ton adresse pour pouvoir te connecter.
      </CardDescription>
    </CardHeader>
    <CardFooter class="justify-center">
      <Button :disabled="cooldown > 0" @click="resendMail(registeredEmail)">
        {{ cooldown > 0 ? `Renvoyer dans ${cooldown}s` : 'Renvoyer' }}
      </Button>
    </CardFooter>
  </Card>

  <VeeForm
    v-else
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
        class="w-full"
        @submit="
          async event => {
            event.preventDefault()
            const result = await validate()

            if (!result.valid) {
              if (stepIndex === steps.length && !images.length) {
                toast.error('Ajoutez au moins une photo')
              }
              return
            }

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
                <FieldError class="text-right" :errors="errors.slice(0, 1)" />
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
                <FieldError class="text-right" :errors="errors.slice(0, 1)" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="password">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Password</FieldLabel>
                <Password v-bind="componentField" :invalid="!!errors.length" />
                <FieldError class="text-right" :errors="errors.slice(0, 1)" />
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
                <FieldError class="text-right" :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="last_name">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Nom</FieldLabel>
                <Input
                  v-bind="componentField"
                  :aria-invalid="!!errors.length"
                />
                <FieldError class="text-right" :errors="errors" />
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

                <FieldError class="text-right" :errors="errors" />
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

                <FieldError class="text-right" :errors="errors" />
              </Field>
            </VeeField>

            <VeeField
              v-slot="{ componentField, errors, value }"
              name="biography"
            >
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Biographie</FieldLabel>
                <Textarea
                  v-bind="componentField"
                  placeholder="Présente-toi..."
                  :aria-invalid="!!errors.length"
                />

                <div class="flex justify-between gap-2">
                  <FieldError
                    v-if="errors.length"
                    :errors="errors.slice(0, 1)"
                  />
                  <span
                    class="ml-auto text-xs text-muted-foreground"
                    :class="{ 'text-destructive': (value?.length ?? 0) > 142 }"
                  >
                    {{ value?.length ?? 0 }}/142
                  </span>
                </div>
              </Field>
            </VeeField>

            <VeeField v-slot="{ componentField, errors }" name="tags">
              <Field :data-invalid="!!errors.length">
                <FieldLabel>Tags</FieldLabel>

                <TagsInput class="w-75">
                  <TagsInputInput placeholder="Tags..." />
                </TagsInput>

                <FieldError class="text-right" :errors="errors" />
              </Field>
            </VeeField>
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
                    class="absolute right-2 top-2 size-8 opacity-0 transition group-hover:opacity-100 pointer-coarse:opacity-100"
                    aria-label="Supprimer la photo"
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
            Précédent
          </Button>

          <Button
            v-if="processing == false"
            type="submit"
            :disabled="isNextDisabled && stepIndex < steps.length"
          >
            {{ stepIndex === steps.length ? 'Créer mon compte' : 'Suivant' }}
          </Button>
          <Button v-else type="submit" disabled>
            <Spinner />
            Chargement
          </Button>
        </div>
      </form>
    </Stepper>
  </VeeForm>
</template>

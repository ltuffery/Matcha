<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { useForm, Field as VeeField } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { toast } from 'vue-sonner'
import {
  CalendarDate,
  DateFormatter,
  getLocalTimeZone,
  parseDate,
  today,
  type DateValue,
} from '@internationalized/date'
import { Calendar as CalendarIcon, Camera, Loader2, X } from 'lucide-vue-next'

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Button } from '@/components/ui/button'
import { Calendar } from '@/components/ui/calendar'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import {
  Field,
  FieldDescription,
  FieldError,
  FieldGroup,
  FieldLabel,
} from '@/components/ui/field'
import { cn } from '@/lib/utils'
import { useAuthStore } from '@/store/useAuthStore'
import { useUsers } from '@/composable/useUsers'
import type { Profile } from '@/api/users'
import { usePhotos } from '@/composable/usePhotos'

const authStore = useAuthStore()
const photosComposable = usePhotos()

const me = authStore.user

/* ------------------------------------------------------------------ */
/*  Photos                                                            */
/* ------------------------------------------------------------------ */
const MAX_PHOTOS = 5
const MAX_SIZE = 5 * 1024 * 1024 // 5 Mo
const ACCEPTED = ['image/jpeg', 'image/png', 'image/webp']

type Photo = { id: number | null; url: string; uploading?: boolean }

const photos = ref<Photo[]>([])
const fileInput = ref<HTMLInputElement | null>(null)
const objectUrls: string[] = []

const openPicker = () => {
  if (photos.value.length < MAX_PHOTOS) fileInput.value?.click()
}

const onFilesSelected = async (e: Event) => {
  const input = e.target as HTMLInputElement
  const files = Array.from(input.files ?? []).slice(
    0,
    MAX_PHOTOS - photos.value.length,
  )
  input.value = ''

  for (const file of files) {
    if (!ACCEPTED.includes(file.type)) {
      toast.error(`${file.name}: unsupported format`)
      continue
    }
    if (file.size > MAX_SIZE) {
      toast.error(`${file.name}: max 5 MB`)
      continue
    }

    const preview = URL.createObjectURL(file)
    objectUrls.push(preview)

    const photo = ref<Photo>({
      id: null,
      url: preview,
      uploading: true,
    }).value
    photos.value.push(photo)

    try {
      const saved = await photosComposable.upload(file)
      Object.assign(photo, {
        id: saved[0].id,
        url: `http://localhost:8011/api/medias/p/${saved[0].name}`,
        uploading: false,
      })
    } catch {
      photos.value = photos.value.filter(p => p.url !== photo.url)
      toast.error(`${file.name}: upload failed`)
    }
  }
}

const removePhoto = async (index: number) => {
  const photo = photos.value[index]
  if (!photo) return
  try {
    if (photo.id) {
      await photosComposable.remove(photo.id)
    }
    photos.value.splice(index, 1)
  } catch {
    toast.error('Unable to delete photo')
  }
}

onMounted(async () => {
  photos.value = (await photosComposable.fetchAll()).map(value => ({
    uploading: false,
    url: `http://localhost:8011/api/medias/p/${value.name}`,
    id: value.id,
  }))
})

onBeforeUnmount(() => objectUrls.forEach(URL.revokeObjectURL))

const BIO_MAX = 500
const tz = getLocalTimeZone()
const maxBirthdate = today(tz).subtract({ years: 18 }) // 18 year minimum
const minBirthdate = today(tz).subtract({ years: 100 })
const df = new DateFormatter('en-US', { dateStyle: 'long' })

const schema = toTypedSchema(
  z.object({
    firstName: z.string().trim().min(1, 'First name is required').max(50),
    lastName: z.string().trim().min(1, 'Last name is required').max(50),
    birthdate: z
      .string({ required_error: 'Birthdate is required' })
      .min(1, 'Birthdate is required')
      .refine(
        v => parseDate(v).compare(maxBirthdate) <= 0,
        'You must be at least 18 years old',
      ),
    bio: z.string().max(BIO_MAX, `Max ${BIO_MAX} characters`).optional(),
  }),
)

const { handleSubmit, isSubmitting, setFieldValue, values, setErrors } =
  useForm({
    validationSchema: schema,
    initialValues: {
      firstName: me?.first_name ?? '',
      lastName: me?.last_name ?? '',
      birthdate: me?.birthday, // format 'YYYY-MM-DD'
      bio: me?.biography ?? '',
    },
  })

const birthdateValue = computed<DateValue | undefined>({
  get: () => (values.birthdate ? parseDate(values.birthdate) : undefined),
  set: v => setFieldValue('birthdate', v ? v.toString() : ''),
})

const age = computed(() => {
  const b = birthdateValue.value
  if (!b) return null
  const t = today(tz)
  let a = t.year - b.year
  if (t.compare(new CalendarDate(t.year, b.month, b.day)) < 0) a--
  return a
})

const bioLength = computed(() => values.bio?.length ?? 0)

const onSubmit = handleSubmit(async vals => {
  try {
    await authStore.updateProfile({
      first_name: vals.firstName,
      last_name: vals.lastName,
      birthday: vals.birthdate,
      biography: vals.bio ?? '',
    })
    toast.success('Profile updated successfully')
  } catch (e: any) {
    const apiErrors = e?.response?.data?.errors
    if (apiErrors) setErrors(apiErrors)
    toast.error(e?.response?.data?.message ?? 'Update failed')
  }
})
</script>

<template>
  <!-- Photos -->
  <Card>
    <CardHeader>
      <CardTitle>Profile photos</CardTitle>
      <CardDescription>
        Add up to 5 photos. The first one is your main photo.
      </CardDescription>
    </CardHeader>
    <CardContent>
      <input
        ref="fileInput"
        type="file"
        class="hidden"
        accept="image/jpeg,image/png,image/webp"
        multiple
        @change="onFilesSelected"
      />

      <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
        <div
          v-for="i in MAX_PHOTOS"
          :key="i"
          class="relative aspect-square rounded-xl overflow-hidden"
        >
          <template v-if="photos[i - 1]">
            <img
              :src="photos[i - 1].url"
              alt=""
              class="h-full w-full object-cover"
            />
            <span
              v-if="i === 1"
              class="absolute bottom-1 left-1 rounded bg-primary px-1.5 py-0.5 text-[10px] font-medium text-primary-foreground"
            >
              Principal
            </span>
            <div
              v-if="photos[i - 1] && photos[i - 1].uploading"
              class="absolute inset-0 flex items-center justify-center bg-background/60"
            >
              <Loader2 class="h-6 w-6 animate-spin" />
            </div>
            <button
              v-else
              type="button"
              class="absolute top-1 right-1 rounded-full bg-background/80 p-1 hover:bg-background"
              aria-label="Remove photo"
              @click="removePhoto(i - 1)"
            >
              <X class="h-4 w-4" />
            </button>
          </template>

          <button
            v-else
            type="button"
            class="h-full w-full rounded-xl border-2 border-dashed border-muted-foreground/25 flex items-center justify-center hover:border-primary/50 hover:bg-muted/50 transition-colors"
            aria-label="Add photo"
            @click="openPicker"
          >
            <Camera class="h-6 w-6 text-muted-foreground" />
          </button>
        </div>
      </div>
    </CardContent>
  </Card>

  <!-- Infos personnelles -->
  <Card>
    <CardHeader>
      <CardTitle>Personal Information</CardTitle>
      <CardDescription>
        This information is visible on your public profile.
      </CardDescription>
    </CardHeader>
    <CardContent>
      <form @submit="onSubmit">
        <FieldGroup>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <VeeField v-slot="{ field, errors }" name="firstName">
              <Field :data-invalid="!!errors.length">
                <FieldLabel for="firstName">First name</FieldLabel>
                <Input
                  id="firstName"
                  placeholder="Camille"
                  autocomplete="given-name"
                  v-bind="field"
                  :aria-invalid="!!errors.length"
                />
                <FieldError v-if="errors.length" :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ field, errors }" name="lastName">
              <Field :data-invalid="!!errors.length">
                <FieldLabel for="lastName">Last name</FieldLabel>
                <Input
                  id="lastName"
                  placeholder="Dupond"
                  autocomplete="family-name"
                  v-bind="field"
                  :aria-invalid="!!errors.length"
                />
                <FieldError v-if="errors.length" :errors="errors" />
              </Field>
            </VeeField>

            <VeeField v-slot="{ errors }" name="birthdate">
              <Field :data-invalid="!!errors.length">
                <FieldLabel for="birthdate">Birthdate</FieldLabel>
                <Popover>
                  <PopoverTrigger as-child>
                    <Button
                      id="birthdate"
                      type="button"
                      variant="outline"
                      :aria-invalid="!!errors.length"
                      :class="
                        cn(
                          'w-full justify-start text-left font-normal',
                          !birthdateValue && 'text-muted-foreground',
                        )
                      "
                    >
                      <CalendarIcon class="mr-2 h-4 w-4" />
                      {{
                        birthdateValue
                          ? df.format(birthdateValue.toDate(tz))
                          : 'Pick a date'
                      }}
                    </Button>
                  </PopoverTrigger>
                  <PopoverContent class="w-auto p-0" align="start">
                    <Calendar
                      v-model="birthdateValue"
                      :default-placeholder="maxBirthdate"
                      :min-value="minBirthdate"
                      :max-value="maxBirthdate"
                      layout="month-and-year"
                      initial-focus
                    />
                  </PopoverContent>
                </Popover>
                <FieldDescription v-if="age !== null">
                  {{ age }} years old
                </FieldDescription>
                <FieldError v-if="errors.length" :errors="errors" />
              </Field>
            </VeeField>
          </div>

          <VeeField v-slot="{ field, errors }" name="bio">
            <Field :data-invalid="!!errors.length">
              <FieldLabel for="bio">Bio</FieldLabel>
              <Textarea
                id="bio"
                class="min-h-24"
                placeholder="Tell us a little about yourself..."
                :maxlength="BIO_MAX"
                v-bind="field"
                :aria-invalid="!!errors.length"
              />
              <p
                class="text-xs text-right"
                :class="
                  bioLength >= BIO_MAX
                    ? 'text-destructive'
                    : 'text-muted-foreground'
                "
              >
                {{ bioLength }} / {{ BIO_MAX }}
              </p>
              <FieldError v-if="errors.length" :errors="errors" />
            </Field>
          </VeeField>

          <div class="flex justify-end">
            <Button type="submit" :disabled="isSubmitting">
              <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
              {{ isSubmitting ? 'Saving...' : 'Save changes' }}
            </Button>
          </div>
        </FieldGroup>
      </form>
    </CardContent>
  </Card>
</template>

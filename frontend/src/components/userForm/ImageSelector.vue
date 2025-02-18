<script setup>
import {onBeforeMount, ref, watch} from 'vue'

const props = defineProps({
  modelValue: {
    type: Array
  }
})

const images = ref([])
const inputImage = ref()
const emit = defineEmits(['update:modelValue'])

const handleFileUpload = e => {
  for (let index = 0; index < e.target.files.length; index++) {
    if (images.value.length == 5) {
      break
    }

    const element = {
      file: e.target.files[index],
      url: URL.createObjectURL(e.target.files[index]),
    }

    images.value.push(element)
    emit('update:modelValue', images.value)
  }
}

const handleRemoveFileUpload = index => {
  images.value.splice(index, 1)
  inputImage.value.value = ''
  emit('update:modelValue', images.value)
}

watch(() => props.modelValue, () => {
  images.value = props.modelValue ? props.modelValue : []
})

onBeforeMount(() => {
    images.value = props.modelValue ? props.modelValue : []
})
</script>

<template>
  <form>
    <div class="form-control">
      <label class="label">
        <span class="label-text"
          >Select up to {{ 5 - images.length }} photos</span
        >
      </label>
      <div class="grid grid-cols-3 gap-4">
        <div
          v-if="images.length < 5"
          class="relative w-full h-40 border-2 border-primary rounded flex justify-center items-center cursor-pointer"
        >
          <svg
            class="w-9 stroke-primary"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g stroke-width="0"></g>
            <g stroke-linecap="round" stroke-linejoin="round"></g>
            <g>
              <path
                d="M15 12L12 12M12 12L9 12M12 12L12 9M12 12L12 15"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
              <path
                d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
            </g>
          </svg>
          <input
            ref="inputImage"
            @change="handleFileUpload"
            type="file"
            class="w-full h-full opacity-0 absolute cursor-pointer"
            multiple
          />
        </div>
        <div
          class="relative w-full h-40 border-2 border-primary rounded flex justify-center items-center cursor-pointer"
          v-for="(item, index) in images"
          :key="index"
        >
          <img class="object-cover w-full h-full" alt="Image" :src="item.url" />
          <div
            @click="handleRemoveFileUpload(index)"
            class="absolute opacity-0 w-full h-full bg-primary hover:opacity-55 flex items-center justify-center"
          >
            <svg
              class="w-9 stroke-white hover:opacity-100"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g>
                <path
                  d="M15 12H9"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
                <path
                  d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
              </g>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

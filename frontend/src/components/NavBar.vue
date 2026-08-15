<script setup lang="ts">
import ThemeSwitcher from './ThemeSwitcher.vue'
import router from '@/router'

defineProps<{
  largeScreen?: boolean
}>()

const goTo = (link: string) => {
  router.push({ name: link })
}
</script>

<template>
  <header v-if="largeScreen" class="z-50 fixed left-0 h-full w-24 bg-card">
    <div class="flex flex-col h-full justify-between items-center">
      <div class="flex flex-col items-center gap-3 pt-5 w-full">
        <div class="tooltip tooltip-right" data-tip="Home">
          <button
            @click="goTo('browse')"
            class="size-16 bg-background rounded-xl btn"
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
                <path
                  d="M21.6359 12.9579L21.3572 14.8952C20.8697 18.2827 20.626 19.9764 19.451 20.9882C18.2759 22 16.5526 22 13.1061 22H10.8939C7.44737 22 5.72409 22 4.54903 20.9882C3.37396 19.9764 3.13025 18.2827 2.64284 14.8952L2.36407 12.9579C1.98463 10.3208 1.79491 9.00229 2.33537 7.87495C2.87583 6.7476 4.02619 6.06234 6.32691 4.69181L7.71175 3.86687C9.80104 2.62229 10.8457 2 12 2C13.1543 2 14.199 2.62229 16.2882 3.86687L17.6731 4.69181C19.9738 6.06234 21.1242 6.7476 21.6646 7.87495"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
              </g>
            </svg>
          </button>
        </div>

        <div class="tooltip tooltip-right" data-tip="Search">
          <button
            @click="goTo('search')"
            class="size-16 bg-background rounded-xl btn"
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M18.5 18.5L22 22"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
                <path
                  d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
              </g>
            </svg>
          </button>
        </div>

        <div class="tooltip tooltip-right" data-tip="Messages">
          <button
            @click="goTo('chat')"
            class="size-16 bg-background rounded-xl btn"
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C21.4816 5.82475 21.7706 6.69989 21.8985 8"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
                <path
                  d="M18 8L15.8411 9.79908C14.0045 11.3296 13.0861 12.0949 12 12.0949C11.3507 12.0949 10.7614 11.8214 10 11.2744M6 8L6.9 8.75L7.8 9.5"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
              </g>
            </svg>
          </button>
        </div>

        <div class="tooltip tooltip-right" data-tip="Likes">
          <button
            @click="goTo('history')"
            class="size-16 bg-background rounded-xl btn"
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  d="M10.9901 14.3082L11.435 13.7045H11.435L10.9901 14.3082ZM12 8.10615L11.4641 8.63086C11.6052 8.77495 11.7983 8.85615 12 8.85615C12.2017 8.85615 12.3948 8.77495 12.5359 8.63086L12 8.10615ZM13.0099 14.3082L12.565 13.7045L12.565 13.7045L13.0099 14.3082ZM12 14.8103L12 14.0603H12L12 14.8103ZM11.435 13.7045C10.7914 13.2302 9.96746 12.5568 9.31176 11.808C8.63279 11.0325 8.25 10.3064 8.25 9.71476H6.75C6.75 10.8757 7.44886 11.9574 8.18323 12.7961C8.94088 13.6614 9.86191 14.4085 10.5451 14.912L11.435 13.7045ZM8.25 9.71476C8.25 8.60703 8.74454 8.02373 9.25333 7.83348C9.77052 7.6401 10.5951 7.74331 11.4641 8.63086L12.5359 7.58145C11.38 6.40091 9.95456 5.96985 8.72797 6.42849C7.49299 6.89028 6.75 8.14533 6.75 9.71476H8.25ZM13.4549 14.912C14.1381 14.4085 15.0591 13.6614 15.8168 12.7961C16.5511 11.9574 17.25 10.8758 17.25 9.71475H15.75C15.75 10.3064 15.3672 11.0326 14.6882 11.808C14.0325 12.5568 13.2086 13.2302 12.565 13.7045L13.4549 14.912ZM17.25 9.71475C17.25 8.14532 16.507 6.89027 15.272 6.42849C14.0454 5.96985 12.62 6.40091 11.4641 7.58145L12.5359 8.63086C13.4049 7.74331 14.2295 7.6401 14.7467 7.83348C15.2555 8.02373 15.75 8.60702 15.75 9.71475H17.25ZM10.5451 14.912C10.9368 15.2007 11.3752 15.5603 12 15.5603L12 14.0603C11.9852 14.0603 11.9682 14.0626 11.899 14.0252C11.8008 13.972 11.678 13.8836 11.435 13.7045L10.5451 14.912ZM12.565 13.7045C12.322 13.8836 12.1992 13.972 12.101 14.0252C12.0318 14.0626 12.0148 14.0603 12 14.0603L12 15.5603C12.6248 15.5603 13.0632 15.2007 13.4549 14.912L12.565 13.7045Z"
                  fill="currentColor"
                ></path>
              </g>
            </svg>
          </button>
        </div>

        <div class="tooltip tooltip-right" data-tip="Profile">
          <button
            @click="goTo('settings')"
            class="size-16 bg-background rounded-xl btn"
          >
            <svg
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g
                id="SVGRepo_tracerCarrier"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></g>
              <g id="SVGRepo_iconCarrier">
                <circle
                  cx="12"
                  cy="9"
                  r="3"
                  stroke="currentColor"
                  stroke-width="1.5"
                ></circle>
                <path
                  d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
                <path
                  d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                ></path>
              </g>
            </svg>
          </button>
        </div>
      </div>
      <ThemeSwitcher />
    </div>
  </header>

  <header
    v-else
    class="flex justify-evenly items-center bg-card h-16 w-full max-w-3xl"
  >
    <div
      @click="goTo('search')"
      class="hover:bg-muted/60 rounded-full flex items-center justify-center h-full w-full cursor-pointer"
    >
      <svg
        class="size-10 mx-5"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            d="M18.5 18.5L22 22"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
          <path
            d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
        </g>
      </svg>
    </div>

    <div
      @click="goTo('history')"
      class="hover:bg-muted/60 rounded-full flex items-center justify-center h-full w-full cursor-pointer"
    >
      <svg
        class="size-10"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            d="M10.9901 14.3082L11.435 13.7045H11.435L10.9901 14.3082ZM12 8.10615L11.4641 8.63086C11.6052 8.77495 11.7983 8.85615 12 8.85615C12.2017 8.85615 12.3948 8.77495 12.5359 8.63086L12 8.10615ZM13.0099 14.3082L12.565 13.7045L12.565 13.7045L13.0099 14.3082ZM12 14.8103L12 14.0603H12L12 14.8103ZM11.435 13.7045C10.7914 13.2302 9.96746 12.5568 9.31176 11.808C8.63279 11.0325 8.25 10.3064 8.25 9.71476H6.75C6.75 10.8757 7.44886 11.9574 8.18323 12.7961C8.94088 13.6614 9.86191 14.4085 10.5451 14.912L11.435 13.7045ZM8.25 9.71476C8.25 8.60703 8.74454 8.02373 9.25333 7.83348C9.77052 7.6401 10.5951 7.74331 11.4641 8.63086L12.5359 7.58145C11.38 6.40091 9.95456 5.96985 8.72797 6.42849C7.49299 6.89028 6.75 8.14533 6.75 9.71476H8.25ZM13.4549 14.912C14.1381 14.4085 15.0591 13.6614 15.8168 12.7961C16.5511 11.9574 17.25 10.8758 17.25 9.71475H15.75C15.75 10.3064 15.3672 11.0326 14.6882 11.808C14.0325 12.5568 13.2086 13.2302 12.565 13.7045L13.4549 14.912ZM17.25 9.71475C17.25 8.14532 16.507 6.89027 15.272 6.42849C14.0454 5.96985 12.62 6.40091 11.4641 7.58145L12.5359 8.63086C13.4049 7.74331 14.2295 7.6401 14.7467 7.83348C15.2555 8.02373 15.75 8.60702 15.75 9.71475H17.25ZM10.5451 14.912C10.9368 15.2007 11.3752 15.5603 12 15.5603L12 14.0603C11.9852 14.0603 11.9682 14.0626 11.899 14.0252C11.8008 13.972 11.678 13.8836 11.435 13.7045L10.5451 14.912ZM12.565 13.7045C12.322 13.8836 12.1992 13.972 12.101 14.0252C12.0318 14.0626 12.0148 14.0603 12 14.0603L12 15.5603C12.6248 15.5603 13.0632 15.2007 13.4549 14.912L12.565 13.7045Z"
            fill="currentColor"
          ></path>
        </g>
      </svg>
    </div>

    <div
      @click="goTo('browse')"
      class="hover:bg-muted/60 rounded-full flex items-center justify-center h-full w-full cursor-pointer"
    >
      <svg
        class="size-10 mx-5"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            d="M9 16C9.85038 16.6303 10.8846 17 12 17C13.1154 17 14.1496 16.6303 15 16"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
          <path
            d="M21.6359 12.9579L21.3572 14.8952C20.8697 18.2827 20.626 19.9764 19.451 20.9882C18.2759 22 16.5526 22 13.1061 22H10.8939C7.44737 22 5.72409 22 4.54903 20.9882C3.37396 19.9764 3.13025 18.2827 2.64284 14.8952L2.36407 12.9579C1.98463 10.3208 1.79491 9.00229 2.33537 7.87495C2.87583 6.7476 4.02619 6.06234 6.32691 4.69181L7.71175 3.86687C9.80104 2.62229 10.8457 2 12 2C13.1543 2 14.199 2.62229 16.2882 3.86687L17.6731 4.69181C19.9738 6.06234 21.1242 6.7476 21.6646 7.87495"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
        </g>
      </svg>
    </div>

    <div
      @click="goTo('chat')"
      class="hover:bg-muted/60 rounded-full flex items-center justify-center h-full w-full cursor-pointer"
    >
      <svg
        class="size-10 mx-5"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            d="M22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C21.4816 5.82475 21.7706 6.69989 21.8985 8"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
          <path
            d="M18 8L15.8411 9.79908C14.0045 11.3296 13.0861 12.0949 12 12.0949C11.3507 12.0949 10.7614 11.8214 10 11.2744M6 8L6.9 8.75L7.8 9.5"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
        </g>
      </svg>
    </div>

    <div
      @click="goTo('settings')"
      class="hover:bg-muted/60 rounded-full flex items-center justify-center h-full w-full cursor-pointer"
    >
      <svg
        class="size-10 mx-5"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <circle
            cx="12"
            cy="9"
            r="3"
            stroke="currentColor"
            stroke-width="1.5"
          ></circle>
          <path
            d="M17.9691 20C17.81 17.1085 16.9247 15 11.9999 15C7.07521 15 6.18991 17.1085 6.03076 20"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
          <path
            d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
          ></path>
        </g>
      </svg>
    </div>
  </header>
</template>

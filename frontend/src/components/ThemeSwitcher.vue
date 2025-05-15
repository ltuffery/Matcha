<script setup>
import { ref, watch } from 'vue'

const picked = ref(localStorage.getItem('theme') || 'default')

watch(picked, newTheme => {
  localStorage.setItem('theme', newTheme)
})
</script>

<template>
  <div class="dropdown dropdown-right dropdown-end">
    <div
      tabindex="0"
      role="button"
      class="w-20 h-8 flex items-center justify-center rounded-xl bg-base-100 mb-5"
    >
      <span class="text-sm">Theme</span>
      <svg
        viewBox="0 0 24 24"
        class="size-4"
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
            d="M9 5L11 7.33333M9 19L15 12L13.5 10.25"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></path>
        </g>
      </svg>
    </div>
    <ul
      tabindex="0"
      class="dropdown-content bg-base-300 rounded-box z-[1] w-52 p-2 shadow-2xl"
    >
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Default"
          value="default"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Dark"
          value="dark"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Cyberpunk"
          value="cyberpunk"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Sunset"
          value="sunset"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Aqua"
          value="aqua"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Romance Light"
          value="romance-light"
          v-model="picked"
        />
      </li>
      <li>
        <input
          type="radio"
          name="theme-dropdown"
          class="theme-controller btn btn-sm btn-block btn-ghost justify-start"
          aria-label="Romance Dark"
          value="romance-dark"
          v-model="picked"
        />
      </li>
    </ul>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {getSocket} from "@/plugins/socket.js";

const sortedRules = ref([
  {
    title: "Age",
    checked: false,
  },
  {
    title: "Location",
    checked: false,
  },
  {
    title: "Fame Rating",
    checked: false,
  },
  {
    title: "Common Tags",
    checked: false,
  }
])

const handlerChange = e => {
  const rules = sortedRules.value.filter(rule => rule.checked)

  getSocket().emit("filter", rules.map(
    rule => rule.title.toLowerCase().replace(" ", '_')
  ))
}
</script>

<template>
  <div class="dropdown dropdown-end">
    <button tabindex="0" class="btn btn-ghost z-20">
      <svg class="w-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
        <g stroke-width="0"></g>
        <g stroke-linecap="round" stroke-linejoin="round"></g>
        <g>
          <path
            d="M9.5 14C11.1569 14 12.5 15.3431 12.5 17C12.5 18.6568 11.1569 20 9.5 20C7.84315 20 6.5 18.6568 6.5 17C6.5 15.3431 7.84315 14 9.5 14Z"
            stroke="currentColor" stroke-width="1.5"></path>
          <path
            d="M14.5 3.99998C12.8431 3.99998 11.5 5.34312 11.5 6.99998C11.5 8.65683 12.8431 9.99998 14.5 9.99998C16.1569 9.99998 17.5 8.65683 17.5 6.99998C17.5 5.34312 16.1569 3.99998 14.5 3.99998Z"
            stroke="currentColor" stroke-width="1.5"></path>
          <path d="M11.0001 7.00001L6.0001 7M3.00002 7.00001L2 7.00001" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round"></path>
          <path d="M13 17L18 17M21.0001 17L22.0001 17" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round"></path>
          <path d="M2 17L6 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
          <path d="M22 7L18 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
        </g>
      </svg>
    </button>
    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
      <li v-for="(rule, index) in sortedRules" :key="index">
        <label class="cursor-pointer">
          <input @change="handlerChange" v-model="rule.checked" type="checkbox" :checked="rule.checked" class="checkbox checkbox-primary"/>
          <span>{{ rule.title }}</span>
        </label>
      </li>
    </ul>
  </div>
</template>

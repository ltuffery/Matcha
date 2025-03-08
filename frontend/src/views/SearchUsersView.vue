<script setup lang="ts">
import UserSearchCard from '@/components/UserSearchCard.vue'
import { Api } from '@/utils/api'
import {onBeforeMount, ref, watch} from 'vue'
import DoubleSlide from "@/components/DoubleSlide.vue";

const search = ref('')
const users = ref([])
const seachCretiria = ref([])

const input = () => {
  if (search.value.length >= 2) {
    Api.get(`search/users?q=${search.value}`)
      .send()
      .then(res => res.json())
      .then(data => (users.value = data))
  } else {
    users.value = []
  }
}

onBeforeMount(() => {
  seachCretiria.value.age = {t1: 18, t2: 80}
  seachCretiria.value.fame = {t1: 0, t2: 99}
})

function testSearch(){
  console.log(seachCretiria.value)
}
</script>

<template>
  <div class="flex flex-col gap-10">
    <div class="flex mt-3">
      <div class="dropdown dropdown-start w-full">
        <div tabindex="0" role="button" class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2">
          <div class="flex text-base">
            <span>Location</span>
            <svg class="size-6 ml-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
            </svg>
          </div>
          <div>No criteria</div>
        </div>
        <div
          tabindex="0"
          class="dropdown-content card card-compact bg-base-100 z-[1] w-64 p-2 shadow"
        >
          <div class="card-body">
            <h3 class="card-title">Card title!</h3>
            <p>you can use any element as a dropdown.</p>
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown w-full">
        <div tabindex="0" role="button" class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1">
          <div class="flex text-base">
            <span>Age gap</span>
            <svg class="size-6 ml-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
            </svg>
          </div>
          <div>{{seachCretiria.age.t1}} - {{seachCretiria.age.t2}}</div>
        </div>
        <div
          tabindex="0"
          class="dropdown-content card card-compact bg-base-100 z-[1] w-64 p-2 shadow"
        >
          <div class="card-body">
            <h3 class="card-title">Set age gap</h3>
              <DoubleSlide
                class="mt-3"
                tooltip
                v-model="seachCretiria.age"
                :min="18"
                :max="80"
                :start="seachCretiria.age.t1"
                :end="seachCretiria.age.t2"
              />
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown w-full">
        <div tabindex="0" role="button" class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1">
          <div class="flex text-base">
            <span>Fame gap</span>
            <svg class="size-6 ml-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
            </svg>
          </div>
          <div>{{seachCretiria.fame.t1}} - {{seachCretiria.fame.t2}}</div>
        </div>
        <div
          tabindex="0"
          class="dropdown-content card card-compact bg-base-100 z-[1] w-64 p-2 shadow"
        >
          <div class="card-body">
            <h3 class="card-title">Set fame rating gap</h3>
            <DoubleSlide
              class="mt-3"
              tooltip
              v-model="seachCretiria.fame"
              :min="0"
              :max="99"
              :start="seachCretiria.fame.t1"
              :end="seachCretiria.fame.t2"
            />
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown dropdown-end w-full">
        <div tabindex="0" role="button" class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1">
          <div class="flex text-base">
            <span>Tags</span>
            <svg class="size-6 ml-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M19 9L12 15L10.25 13.5M5 9L7.33333 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </g>
            </svg>
          </div>
          <div>All</div> <!-- Or x selected -->
        </div>
        <div
          tabindex="0"
          class="dropdown-content card card-compact bg-base-100 z-[1] w-64 p-2 shadow"
        >
          <div class="card-body">
            <h3 class="card-title">Card title!</h3>
            <p>you can use any element as a dropdown.</p>
          </div>
        </div>


      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="flex items-center justify-center w-full">
        <button class="btn btn-neutral" @click="testSearch">Search</button>
      </div>

    </div>

    <div>
      <div class="flex justify-between w-full py-2 bg-base-300/60">
        <div class="flex items-center">


          <div class="dropdown dropdown-start ml-3">
            <div tabindex="0" role="button" class="size-8">
              <svg class="w-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path d="M4 16L13 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M6 11H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M8 6L13 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M17 4L17 20L20 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
              <li class="w-full flex items-center text-base">Sort by</li>
              <div class="divider my-0"></div>
              <li>
                <div class="form-control">
                  <label class="label cursor-pointer">
                    <input type="checkbox" class="checkbox" />
                    <span class="label-text ml-2">Age</span>
                  </label>
                </div>
              </li>
              <li>
                <div class="flex flex-col">
                  <span>Age gap</span>
                  <double-slide />
                </div>
              </li>
              <li>
                <button class="btn"> <!-- Useless ? -->
                  Apply
                </button>
              </li>
            </ul>
          </div>



        </div>
        <div class="flex items-center">

          <div class="dropdown dropdown-end mr-3">
            <div tabindex="0" role="button" class="size-8">
              <svg class="w-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path d="M20.058 9.72255C21.0065 9.18858 21.4808 8.9216 21.7404 8.49142C22 8.06124 22 7.54232 22 6.50448V5.81466C22 4.48782 22 3.8244 21.5607 3.4122C21.1213 3 20.4142 3 19 3H5C3.58579 3 2.87868 3 2.43934 3.4122C2 3.8244 2 4.48782 2 5.81466V6.50448C2 7.54232 2 8.06124 2.2596 8.49142C2.5192 8.9216 2.99347 9.18858 3.94202 9.72255L6.85504 11.3624C7.49146 11.7206 7.80967 11.8998 8.03751 12.0976C8.51199 12.5095 8.80408 12.9935 8.93644 13.5872C9 13.8722 9 14.2058 9 14.8729L9 17.5424C9 18.452 9 18.9067 9.25192 19.2613C9.50385 19.6158 9.95128 19.7907 10.8462 20.1406C12.7248 20.875 13.6641 21.2422 14.3321 20.8244C15 20.4066 15 19.4519 15 17.5424V14.8729C15 14.2058 15 13.8722 15.0636 13.5872C15.1959 12.9935 15.488 12.5095 15.9625 12.0976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </g>
              </svg>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
              <li class="w-full flex items-center text-base">Filter by</li>
              <div class="divider my-0"></div>
              <li><a>Item 1</a></li>
              <li><a>Item 2</a></li>
            </ul>
          </div>

        </div>
      </div>
      <div class="pt-3 px-3">content</div>
    </div>
  </div>

  <!-- ##### Old Code ##### -->
  <!--  <div class="max-w-3xl h-full m-auto pt-8">-->
  <!--    <label class="input input-bordered flex items-center gap-2">-->
  <!--      <input-->
  <!--        @input="input"-->
  <!--        v-model="search"-->
  <!--        type="text"-->
  <!--        class="grow"-->
  <!--        placeholder="Search a user"-->
  <!--      />-->
  <!--      <svg-->
  <!--        xmlns="http://www.w3.org/2000/svg"-->
  <!--        viewBox="0 0 24 24"-->
  <!--        fill="none"-->
  <!--        class="h-4 w-4 opacity-70"-->
  <!--      >-->
  <!--        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>-->
  <!--        <g-->
  <!--          id="SVGRepo_tracerCarrier"-->
  <!--          stroke-linecap="round"-->
  <!--          stroke-linejoin="round"-->
  <!--        ></g>-->
  <!--        <g id="SVGRepo_iconCarrier">-->
  <!--          <path-->
  <!--            d="M18.5 18.5L22 22"-->
  <!--            stroke="currentColor"-->
  <!--            stroke-width="1.5"-->
  <!--            stroke-linecap="round"-->
  <!--          ></path>-->
  <!--          <path-->
  <!--            d="M6.75 3.27093C8.14732 2.46262 9.76964 2 11.5 2C16.7467 2 21 6.25329 21 11.5C21 16.7467 16.7467 21 11.5 21C6.25329 21 2 16.7467 2 11.5C2 9.76964 2.46262 8.14732 3.27093 6.75"-->
  <!--            stroke="currentColor"-->
  <!--            stroke-width="1.5"-->
  <!--            stroke-linecap="round"-->
  <!--          ></path>-->
  <!--        </g>-->
  <!--      </svg>-->
  <!--    </label>-->
  <!--    <div-->
  <!--      class="p-6 flex flex-col gap-5 border-2 rounded-box border-neutral-content mt-2"-->
  <!--      v-if="users.length"-->
  <!--    >-->
  <!--      <UserSearchCard-->
  <!--        v-for="user in users"-->
  <!--        :key="user"-->
  <!--        :username="user.username"-->
  <!--        :avatar="user.avatar"-->
  <!--      />-->
  <!--    </div>-->
  <!--  </div>-->
</template>

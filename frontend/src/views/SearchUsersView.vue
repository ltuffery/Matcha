<script setup lang="ts">
import UserSearchCard from '@/components/UserSearchCard.vue'
import UserPreviewCard from '@/components/UserPreviewCard.vue'
import TagSelector from '@/components/TagSelector.vue'
import { Api } from '@/utils/api'
import { onBeforeMount, ref, watch } from 'vue'
import DoubleSlide from '@/components/DoubleSlide.vue'
import { Tracking } from '@/services/tracking.js'

const search = ref('')
const users = ref([])
const searchCriteria = ref([])
const sortStatus = ref({
  age: 0,
  location: 0,
  fameRate: 0,
  tag: 0,
})

const locationParam = ref({
  cityList: '',
  countryCode: 'FR',
})

const filter = ref({
  age: [
    { age: 22, selected: false },
    { age: 24, selected: false },
    { age: 32, selected: false },
    { age: 1423, selected: false },
    { age: 21, selected: false },
    { age: 76, selected: false },
    { age: 55, selected: false },
  ],
  location: [
    { name: 'Tours', selected: false },
    { name: 'Other name', selected: false },
    { name: 'Random name', selected: false },
    { name: 'A Random name very too long for the dropdown', selected: false },
    { name: 'Another name', selected: false },
    { name: 'etc', selected: false },
    { name: 'Toulouse', selected: false },
  ],
  fame: [
    { name: '12', selected: false },
    { name: '32', selected: false },
    { name: '23', selected: false },
    { name: '45', selected: false },
  ],
  tags: [
    { name: 'ouioui', selected: false },
    { name: 'baguette', selected: false },
    { name: 'bike', selected: false },
    { name: 'rando', selected: false },
  ],
})

// const input = () => {
//   if (search.value.length >= 2) {
//     Api.get(`search/users?q=${search.value}`)
//       .send()
//       .then(res => res.json())
//       .then(data => (users.value = data))
//   } else {
//     users.value = []
//   }
// }

// -------------- To debug --------------
const printInfo = () => {
  console.log(users.value)
}

const getImageOf = value => {
  switch (value) {
    case 0:
      return `<svg class="w-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </g>
              </svg>`
    case 1:
      return `<svg class="w-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path d="M4 16L13 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M6 11H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M8 6L13 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M17 4L17 20L20 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>`
    case 2:
      return `<svg class="w-full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path d="M4 8H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M6 13H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M8 18H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M17 20V4L20 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>`
    default:
      break
  }
}

const changeState = value => {
  switch (value) {
    case 'age':
      if (sortStatus.value.age == 2) sortStatus.value.age = 0
      else sortStatus.value.age++
      break

    case 'location':
      if (sortStatus.value.location == 2) sortStatus.value.location = 0
      else sortStatus.value.location++
      break

    case 'fameRate':
      if (sortStatus.value.fameRate == 2) sortStatus.value.fameRate = 0
      else sortStatus.value.fameRate++
      break

    case 'tag':
      if (sortStatus.value.tag == 2) sortStatus.value.tag = 0
      else sortStatus.value.tag++
      break
  }
}

const addLocation = e => {
  searchCriteria.value.loc.push({
    'name': e.currentTarget.getAttribute('name'),
    'lat': e.currentTarget.getAttribute('lat'),
    'lon': e.currentTarget.getAttribute('lon'),
  })
}

const removeLocation = e => {
  searchCriteria.value.loc.splice(e.currentTarget.getAttribute('index'), 1);
}

const refreshCityList = async e => {
  locationParam.value.cityList = await Tracking.getCityListByName(
    e.target.value,
    locationParam.value.countryCode
  )
}

onBeforeMount(() => {
  searchCriteria.value.age = { t1: 18, t2: 80 }
  searchCriteria.value.fame = { t1: 0, t2: 100 }
  searchCriteria.value.loc = []
})

async function testSearch() {
  searchCriteria.value.age = Object.values(searchCriteria.value.age);
  searchCriteria.value.fame = Object.values(searchCriteria.value.fame);
  console.log(searchCriteria.value)
  let response = await Api.post('/search/users').send(searchCriteria.value);
  response = await response.json()
  console.log(response)
}
</script>

<template>
  <div class="flex flex-col h-full">
    <div class="flex mt-3">
      <div class="dropdown dropdown-start w-full">
        <div
          tabindex="0"
          role="button"
          class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2"
        >
          <div class="flex text-base">
            <span>Location</span>
            <svg
              class="size-6 ml-1"
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
                  d="M19 9L12 15L10.25 13.5M5 9L7.33333 11"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
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
            <h3 class="card-title">Location</h3>
            <div
              class="flex items-center gap-1"
              v-for="(city, index) in searchCriteria.loc"
              :key="index"
              :name="city.name">
              <svg @click="removeLocation" :index="index" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-6 text-error opacity-60 hover:opacity-100" >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <path d="M9 10H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                  <path d="M5 15.2161C4.35254 13.5622 4 11.8013 4 10.1433C4 5.64588 7.58172 2 12 2C16.4183 2 20 5.64588 20 10.1433C20 14.6055 17.4467 19.8124 13.4629 21.6744C12.5343 22.1085 11.4657 22.1085 10.5371 21.6744C9.26474 21.0797 8.13831 20.1439 7.19438 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </g>
              </svg>
              <span>{{ city.name }}</span>
            </div>


            <!-- <p>Toulouse</p> -->
            <input type="text" class="grow input input-bordered" placeholder="Toulouse" @input="refreshCityList" />
            <div class="w-full max-h-48">
              <div
                v-for="(city, index) in locationParam.cityList"
                @click="addLocation"
                :key="index"
                :lat="city.lat"
                :lon="city.lng"
                :name="city.toponymName"
                class="hover:bg-base-300 cursor-pointer"
              >
                {{ city.toponymName }}
              </div>
            </div>


          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown w-full">
        <div
          tabindex="0"
          role="button"
          class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1"
        >
          <div class="flex text-base">
            <span>Age gap</span>
            <svg
              class="size-6 ml-1"
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
                  d="M19 9L12 15L10.25 13.5M5 9L7.33333 11"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </g>
            </svg>
          </div>
          <div>{{ searchCriteria.age.t1 }} - {{ searchCriteria.age.t2 }}</div>
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
              v-model="searchCriteria.age"
              :min="18"
              :max="80"
              :start="searchCriteria.age.t1"
              :end="searchCriteria.age.t2"
            />
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown w-full">
        <div
          tabindex="0"
          role="button"
          class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1"
        >
          <div class="flex text-base">
            <span>Fame gap</span>
            <svg
              class="size-6 ml-1"
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
                  d="M19 9L12 15L10.25 13.5M5 9L7.33333 11"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </g>
            </svg>
          </div>
          <div>{{ searchCriteria.fame.t1 }} - {{ searchCriteria.fame.t2 }}</div>
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
              v-model="searchCriteria.fame"
              :min="0"
              :max="100"
              :start="searchCriteria.fame.t1"
              :end="searchCriteria.fame.t2"
            />
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="dropdown dropdown-end w-full">
        <div
          tabindex="0"
          role="button"
          class="hover:bg-base-300 rounded-lg font-semibold select-none cursor-pointer w-full text-sm px-3 py-2 m-1"
        >
          <div class="flex text-base">
            <span>Tags</span>
            <svg
              class="size-6 ml-1"
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
                  d="M19 9L12 15L10.25 13.5M5 9L7.33333 11"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></path>
              </g>
            </svg>
          </div>
          <div>{{ searchCriteria.tags && searchCriteria.tags.length > 0 ? searchCriteria.tags.length + ' Selected' : 'All' }}</div>
        </div>
        <div
          tabindex="0"
          class="dropdown-content card card-compact bg-base-100 z-[1] w-64 p-2 shadow"
        >
          <div class="card-body max-h-96 overflow-y-auto">
            <h3 class="card-title">Select tags</h3>
            <TagSelector v-model="searchCriteria.tags" />
          </div>
        </div>
      </div>

      <div class="divider divider-horizontal mx-0 py-3"></div>

      <div class="flex items-center justify-center w-full">
        <button class="btn btn-neutral" @click="testSearch">Search</button>
      </div>
    </div>

    <div class="pt-8">
      <div class="flex justify-between w-full py-2 bg-base-300/60">
        <div class="flex items-center">
          <div class="dropdown dropdown-start ml-3">
            <div tabindex="0" role="button" class="size-8">
              <svg
                class="w-full"
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
                    d="M4 16L13 16"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                  <path
                    d="M6 11H13"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                  <path
                    d="M8 6L13 6"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                  <path
                    d="M17 4L17 20L20 16"
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
              class="dropdown-content menu bg-base-100 rounded-box z-[1] w-48 p-2 shadow"
            >
              <li class="w-full flex items-center text-base">Sort by</li>
              <div class="divider my-0"></div>
              <li @click="changeState('age')">
                <div class="form-control">
                  <span
                    class="size-6"
                    v-html="getImageOf(sortStatus.age)"
                  ></span>
                  <span class="text-base ml-2">Age</span>
                </div>
              </li>
              <li @click="changeState('location')">
                <div class="form-control">
                  <span
                    class="size-6"
                    v-html="getImageOf(sortStatus.location)"
                  ></span>
                  <span class="text-base ml-2">Location</span>
                </div>
              </li>
              <li @click="changeState('fameRate')">
                <div class="form-control">
                  <span
                    class="size-6"
                    v-html="getImageOf(sortStatus.fameRate)"
                  ></span>
                  <span class="text-base ml-2">Fame rate</span>
                </div>
              </li>
              <li @click="changeState('tag')">
                <div class="form-control">
                  <span
                    class="size-6"
                    v-html="getImageOf(sortStatus.tag)"
                  ></span>
                  <span class="text-base ml-2">Tags</span>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!--     ------------------------ Start of filter dropdown ------------------------    -->
        <div class="flex items-center">





          <div class="dropdown dropdown-end mr-3">
            <label tabindex=0 class="size-8 cursor-pointer">
              <svg
                class="size-8"
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
                    d="M20.058 9.72255C21.0065 9.18858 21.4808 8.9216 21.7404 8.49142C22 8.06124 22 7.54232 22 6.50448V5.81466C22 4.48782 22 3.8244 21.5607 3.4122C21.1213 3 20.4142 3 19 3H5C3.58579 3 2.87868 3 2.43934 3.4122C2 3.8244 2 4.48782 2 5.81466V6.50448C2 7.54232 2 8.06124 2.2596 8.49142C2.5192 8.9216 2.99347 9.18858 3.94202 9.72255L6.85504 11.3624C7.49146 11.7206 7.80967 11.8998 8.03751 12.0976C8.51199 12.5095 8.80408 12.9935 8.93644 13.5872C9 13.8722 9 14.2058 9 14.8729L9 17.5424C9 18.452 9 18.9067 9.25192 19.2613C9.50385 19.6158 9.95128 19.7907 10.8462 20.1406C12.7248 20.875 13.6641 21.2422 14.3321 20.8244C15 20.4066 15 19.4519 15 17.5424V14.8729C15 14.2058 15 13.8722 15.0636 13.5872C15.1959 12.9935 15.488 12.5095 15.9625 12.0976"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                </g>
              </svg>
            </label>
            <ul tabIndex=0 class="menu bg-base-200 w-56 rounded-box dropdown-content z-[1]">
              <li class="w-full flex items-center text-base">Filter by</li>
              <div class="divider my-0"></div>
              <li>
                <details>
                  <summary>Age</summary>
                  <ul class="max-w-48">
                    <div class="flex flex-wrap gap-1 max-h-40 overflow-y-auto">
                      <label
                        v-for="(obj, index) in filter.age"
                        :key="index"
                        class="flex items-center w-fit cursor-pointer p-1 rounded-lg hover:bg-base-200"
                      >
                        <input
                          v-model="obj.selected"
                          type="checkbox"
                          class="checkbox size-5"
                        />
                        <span class="label-text ml-2">{{ obj.age }}</span>
                      </label>
                    </div>
                  </ul>
                </details>
              </li>
              <li>
                <details>
                  <summary>Location</summary>
                  <ul class="max-w-48">
                    <div class="flex flex-col gap-1 max-h-60 overflow-y-auto">
                      <label
                        v-for="(obj, index) in filter.location"
                        :key="index"
                        class="flex items-center w-fit cursor-pointer p-1 rounded-lg hover:bg-base-200"
                      >
                        <input
                          v-model="obj.selected"
                          type="checkbox"
                          class="checkbox size-5"
                        />
                        <span class="label-text ml-2">{{ obj.name }}</span>
                      </label>
                    </div>
                  </ul>
                </details>
              </li>
              <li>
                <details>
                  <summary>Fame rate</summary>
                  <ul class="max-w-48">
                    <div class="flex flex-wrap gap-1 max-h-40 overflow-y-auto">
                      <label
                        v-for="(obj, index) in filter.fame"
                        :key="index"
                        class="flex items-center w-fit cursor-pointer p-1 rounded-lg hover:bg-base-200"
                      >
                        <input
                          v-model="obj.selected"
                          type="checkbox"
                          class="checkbox size-5"
                        />
                        <span class="label-text ml-2">{{ obj.name }}</span>
                      </label>
                    </div>
                  </ul>
                </details>
              </li>
              <li>
                <details>
                  <summary>Tags</summary>
                  <ul class="max-w-48">
                    <div class="flex flex-wrap gap-1 max-h-40 overflow-y-auto">
                      <label
                        v-for="(obj, index) in filter.tags"
                        :key="index"
                        class="flex items-center w-fit cursor-pointer p-1 rounded-lg hover:bg-base-200"
                      >
                        <input
                          v-model="obj.selected"
                          type="checkbox"
                          class="checkbox size-5"
                        />
                        <span class="label-text ml-2">{{ obj.name }}</span>
                      </label>
                    </div>
                  </ul>
                </details>
              </li>
            </ul>
          </div>

          <!--     ------------------------ End of filter dropdown ------------------------    -->
        </div>
      </div>

<!--      <button class="btn" @click="printInfo">Debug</button>-->
    </div>
    <div class="py-8 overflow-y-auto w-full flex flex-wrap justify-center gap-5 z-0">
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
      <UserPreviewCard username="Leo" age="104" avatar="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />

      <span>By security, the search is limited by 25 users only</span>
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

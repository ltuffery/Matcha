<script setup>
import { ref } from 'vue'
import BirthdaySelector from '@/components/userForm/BirthdaySelector.vue'

const email = ref()
const firstName = ref()
const lastName = ref()

const changeEntity = e => {
  switch (e) {
    case 'email':
      email.value.disabled = false
      email.value.focus()
      break
    case 'fname':
      firstName.value.disabled = false
      firstName.value.focus()
      break
    case 'lname':
      lastName.value.disabled = false
      lastName.value.focus()
      break
    default:
      break
  }
}

const sendChangement = e => {
  if (e.key === 'Enter') {
    e.target.blur()
    return
  }
  switch (e.target.id) {
    case 'email':
      console.log('I send ', e.target.value)
      email.value.disabled = true
      break
    case 'fname':
      console.log('I send ', e.target.value)
      firstName.value.disabled = true
      break
    case 'lname':
      console.log('I send ', e.target.value)
      lastName.value.disabled = true
      break
    case 'gender':
      console.log('I send ', e.target.value)
      break
    case 'birthday':
      console.log('I send ', e.target.value)
      break
    default:
      break
  }
}
</script>

<template>
  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <label>Email :</label>
      <div class="flex w-full gap-2">
        <label class="input input-bordered w-full flex items-center gap-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="h-4 w-4 opacity-70"
          >
            <path
              d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"
            />
            <path
              d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"
            />
          </svg>
          <input
            ref="email"
            v-on:keyup.enter="sendChangement"
            @blur="sendChangement"
            id="email"
            type="text"
            class="grow"
            placeholder="Email"
            value="test@test.test"
            disabled
          />
        </label>

        <button class="btn" @click="changeEntity('email')">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            class="size-4"
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
                d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
              ></path>
            </g>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex flex-col w-full gap-4">
        <div class="flex flex-col gap-2">
          <label>First Name (Display name) :</label>
          <div class="flex w-full gap-2">
            <input
              ref="firstName"
              v-on:keyup.enter="sendChangement"
              @blur="sendChangement"
              id="fname"
              type="text"
              placeholder="First Name"
              class="input input-bordered w-full"
              value="tmpName"
              disabled
            />
            <button class="btn" @click="changeEntity('fname')">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                class="size-4"
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
                    d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                </g>
              </svg>
            </button>
          </div>
        </div>

        <div class="flex flex-col gap-2">
          <label>Last Name :</label>
          <div class="flex w-full gap-2">
            <input
              ref="lastName"
              v-on:keyup.enter="sendChangement"
              @blur="sendChangement"
              id="lname"
              type="text"
              placeholder="First Name"
              class="input input-bordered w-full"
              value="tmpName"
              disabled
            />
            <button class="btn" @click="changeEntity('lname')">
              <svg
                viewBox="0 0 24 24"
                fill="none"
                class="size-4"
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
                    d="M14.3601 4.07866L15.2869 3.15178C16.8226 1.61607 19.3125 1.61607 20.8482 3.15178C22.3839 4.68748 22.3839 7.17735 20.8482 8.71306L19.9213 9.63993M14.3601 4.07866C14.3601 4.07866 14.4759 6.04828 16.2138 7.78618C17.9517 9.52407 19.9213 9.63993 19.9213 9.63993M14.3601 4.07866L12 6.43872M19.9213 9.63993L14.6607 14.9006L11.5613 18L11.4001 18.1612C10.8229 18.7383 10.5344 19.0269 10.2162 19.2751C9.84082 19.5679 9.43469 19.8189 9.00498 20.0237C8.6407 20.1973 8.25352 20.3263 7.47918 20.5844L4.19792 21.6782M4.19792 21.6782L3.39584 21.9456C3.01478 22.0726 2.59466 21.9734 2.31063 21.6894C2.0266 21.4053 1.92743 20.9852 2.05445 20.6042L2.32181 19.8021M4.19792 21.6782L2.32181 19.8021M2.32181 19.8021L3.41556 16.5208C3.67368 15.7465 3.80273 15.3593 3.97634 14.995C4.18114 14.5653 4.43213 14.1592 4.7249 13.7838C4.97308 13.4656 5.26166 13.1771 5.83882 12.5999L8.5 9.93872"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                  ></path>
                </g>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <label>Birthday :</label>
      <birthday-selector @input="sendChangement" id="birthday" />
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <label>Gender :</label>
      <select
        class="select select-bordered w-full max-w-xs"
        @change="sendChangement"
        id="gender"
      >
        <option disabled selected>Choose one</option>
        <option value="M">Man</option>
        <option value="F">Woman</option>
        <option value="O">Other</option>
      </select>
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <button class="btn">Change Your Password</button>
    </div>
  </div>

  <div>
    <div class="card w-full p-5 mt-10">
      <button class="btn btn-outline btn-error">Delete Account</button>
    </div>
  </div>
</template>

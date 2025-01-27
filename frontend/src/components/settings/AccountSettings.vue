<script setup>

import TagSelector from "@/components/TagSelector.vue";
import ImageSelector from "@/components/userForm/ImageSelector.vue";
</script>

<template>
  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
        <label>Images :</label>
      <ImageSelector />
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Bio :</label>
        <label>X / 512</label>
      </div>
      <textarea class="textarea textarea-bordered" style="resize: none" placeholder="Bio"></textarea>
    </div>
  </div>

  <div>
    <div class="card bg-base-300 gap-3 w-full p-5">
      <div class="flex justify-between">
        <label>Tags :</label>
        <label>X Selected</label>
      </div>
      <TagSelector class="max-h-72 overflow-y-auto" />
    </div>
  </div>

  <div>
    <div class="card w-full p-5 mt-10">
      <button class="btn btn-outline btn-error">Delete Account</button>
    </div>
  </div>
</template>

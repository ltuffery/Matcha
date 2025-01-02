<template>
  <div class="flex max-w-4xl m-auto pt-8 rounded-box border-1 border-neutral-content">
    <div class="flex flex-col min-w-60 m-auto border-r-1">
      <div class="flex gap-4 items-center">
        <div class="avatar">
          <div class="mask mask-squircle w-16">
            <img
              src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
            />
          </div>
        </div>
        <div class="flex flex-col relative">
          <span class="font-bold">Username</span>
          <p class="truncate whitespace-nowrap overflow-hidden text-ellipsis text-sm">
            Last message of user
          </p>
        </div>
      </div>
      
    </div>

    <!-- Conversation box -->
    <div class="hidden md:block p-8">
      <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quisquam sit quaerat doloribus ullam aliquid provident minus esse nulla maiores exercitationem nesciunt quos optio beatae debitis tenetur earum, eligendi obcaecati? Suscipit.</p>
    </div>
  </div>
</template>
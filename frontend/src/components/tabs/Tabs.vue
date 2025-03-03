<script lang="ts">
import {
  defineComponent,
  reactive,
  provide,
  onMounted,
  onBeforeMount,
  toRefs,
  VNode,
} from 'vue'

export interface TabProps {
  title: string
}

export default defineComponent({
  name: 'Tabs',
  setup(_, { slots }) {
    const state = reactive({
      selectedIndex: 0,
      tabs: [] as VNode<TabProps>[],
      count: 0,
    })

    provide('TabsProvider', state)

    const selectTab = (i: number) => {
      state.selectedIndex = i
    }

    onBeforeMount(() => {
      if (slots.default) {
        state.tabs = slots.default().filter(child => child.type.name === 'Tab')
      }
    })

    onMounted(() => {
      selectTab(0)
    })

    return { ...toRefs(state), selectTab }
  },
})
</script>

<template>
  <div>
    <div class="tabs bg-base-200 rounded-btn w-fit space-x-1 overflow-x-auto p-1 rtl:space-x-reverse" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        @click="selectTab(index)"
        :class="{ 'active': selectedIndex === index }"
        type="button" class="btn btn-text active-tab:bg-primary active-tab:text-white hover:text-primary hover:bg-transparent" id="tabs-segment-item-1" data-tab="#tabs-segment-1" aria-controls="tabs-segment-1" role="tab" aria-selected="true"
      >
        {{ tab.props.name }}
      </div>
    </div>
    <div>
      <slot></slot>
    </div>
  </div>
</template>

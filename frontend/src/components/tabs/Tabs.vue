<script lang="ts">
import {defineComponent, reactive, provide, onMounted, onBeforeMount, toRefs, VNode} from "vue";

interface TabProps {
  title: string;
}

export default defineComponent({
  name: "Tabs",
  setup(_, {slots}) {
    const state = reactive({
      selectedIndex: 0,
      tabs: [] as VNode<TabProps>[],
      count: 0
    });

    provide("TabsProvider", state);

    const selectTab = (i: number) => {
      state.selectedIndex = i;
    };

    onBeforeMount(() => {
      if (slots.default) {
        state.tabs = slots.default().filter((child) => child.type.name === "Tab");
      }
    });

    onMounted(() => {
      selectTab(0);
    });

    return {...toRefs(state), selectTab};
  }
});
</script>

<template>
  <div>
    <div role="tablist" class="tabs tabs-boxed">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        @click="selectTab(index)"
        :class="{'tab-active': selectedIndex===index}"
        class="tab"
      >
        {{ tab.props.name }}
      </div>
    </div>
    <div>
      <slot></slot>
    </div>
  </div>
</template>

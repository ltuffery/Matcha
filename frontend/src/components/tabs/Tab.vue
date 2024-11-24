<script lang="ts">
import {defineComponent, inject, onBeforeMount, ref, VNode, watch} from "vue";
import { TabProps } from "./Tabs.vue";

export default defineComponent({
  name: "Tab",
  setup() {
    const index = ref(0);
    const isActive = ref(false);

    const tabs = inject("TabsProvider") as {
      selectedIndex: number,
      tabs: VNode<TabProps>[],
      count: number
    };

    watch(
      () => tabs.selectedIndex,
      () => {
        isActive.value = index.value === tabs.selectedIndex;
      }
    );

    onBeforeMount(() => {
      index.value = tabs.count;
      tabs.count++;
      isActive.value = index.value === tabs.selectedIndex;
    });
    return {index, isActive};
  }
});
</script>

<template>
  <div v-show="isActive">
    <slot></slot>
  </div>
</template>

<template>
  <div>
    <div class="md:hidden">
      <label for="tabs" class="sr-only">Select a tab</label>
      <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
      <select
        id="tabs"
        name="tabs"
        class="font-medium block w-full bg-ventamo-gray outline-none border-gray-200 border-t-0 border-l-0 border-r-0"
        @input="(e) => $emit('input', parseInt(e.target.value))"
      >
        <option
          v-for="(option, index) in options"
          :key="index"
          class="font-medium"
          :selected="index === value"
          :value="index"
        >
          {{ option }}
        </option>
      </select>
    </div>
    <div class="hidden md:block border-gray-200">
      <div>
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
          <tab-pane-item
            v-for="(option, index) in options"
            :key="index"
            :name="option"
            :selected="index === value"
            @click="$emit('input', index)"
          >
            <template #content>
              <slot :name="`tab-pane-content-${index}`" :data="option">
                {{ option }}
              </slot>
            </template>
          </tab-pane-item>
        </nav>
      </div>
    </div>
    <div v-for="(option, index) in options" :key="index">
      <slot v-if="index === value" :name="`tab-body-${index}`" />
    </div>
  </div>
</template>

<script>
import TabPaneItem from '~/components/Common/Tabs/components/TabPaneItem'
export default {
  components: { TabPaneItem },
  props: {
    value: {
      type: Number,
      default: 0,
    },
    options: {
      type: Array,
      default: () => [],
    },
  },
}
</script>

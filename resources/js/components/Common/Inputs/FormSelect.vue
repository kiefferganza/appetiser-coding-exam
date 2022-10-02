<template>
  <multiselect
    :value="value"
    :options="options"
    :deselect-label="deselectLabel"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template
      #option="props"
      class="form-select-item form-select-item__primary"
    >
      <div>
        <span class="option__title font-medium">{{ props.option.name }}</span>
        <slot :option="props.option" name="append-title" />
      </div>
    </template>
  </multiselect>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
  components: {
    Multiselect
  },
  props: {
    options: {
      type: Array,
      default: () => []
    },
    deselectLabel: {
      type: String,
      default: ''
    },
    value: {
      type: [Object, Array],
      default: () => {}
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped></style>

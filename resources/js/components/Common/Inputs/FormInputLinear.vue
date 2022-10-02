<template>
  <input
    :class="{ 'w-full': block }"
    v-bind="$attrs"
    :value="value"
    class="form-input_linear"
    placeholder="Jane Smith"
    v-on="inputListeners"
  />
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Number],
      default: '',
    },
    error: {
      type: String,
      default: '',
    },
    block: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    inputListeners() {
      /*
       * Simplified native input workaround for auto
       * binding issue for Vue (sauce: https://github.com/vuejs/vue/issues/7914#issuecomment-381500346)
       */
      return {
        ...this.$listeners,
        input: (e) => this.$emit('input', e.target.value),
      }
    },
  },
}
</script>

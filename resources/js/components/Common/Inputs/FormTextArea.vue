<template>
  <textarea
    v-bind="$attrs"
    :value="value"
    class="form-textarea form-textarea__primary"
    v-on="inputListeners"
  ></textarea>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: '',
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

<style scoped></style>

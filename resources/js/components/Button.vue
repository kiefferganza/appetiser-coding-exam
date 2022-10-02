<template>
  <button :type="nativeType" :disabled="loading" :class="{
    [`button__${type}`]: true,
    'button__block': block,
    'button__lg': large,
    'btn-loading': loading
  }" class="button"
  >
    <slot />
  </button>
</template>

<script>
export default {
  name: 'VButton',

  props: {
    type: {
      type: String,
      default: 'primary'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    loading: {
      type: Boolean,
      default: false
    },

    block: {
      type: Boolean,
      default: false
    },

    large: {
      type: Boolean,
      default: false
    }
  }
}
</script>

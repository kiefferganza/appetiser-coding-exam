<template>
  <div class="alert" :class="variantClass" role="alert">
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    variant: {
      type: String,
      default: 'primary',
    },
  },
  computed: {
    variantClass() {
      switch (this.variant) {
        case 'primary':
          return 'alert__primary'
        case 'info':
          return 'alert__info'
        case 'danger':
          return 'alert__danger'
        case 'success':
          return 'alert__success'
        default:
          return 'alert__primary'
      }
    },
  },
}
</script>

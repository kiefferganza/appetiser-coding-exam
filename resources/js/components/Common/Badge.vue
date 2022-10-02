<template>
  <span class="badge" :class="variantClass">
    {{ title }}
  </span>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    variant: {
      type: String,
      default: 'primary',
    },
  },
  computed: {
    variantClass() {
      switch (this.variant) {
        case 'primary':
          return 'badge__secondary'
        case 'danger':
          return 'badge__danger'
        case 'success':
          return 'badge__success'
        case 'info':
          return 'badge__info'
        case 'warning':
          return 'badge__warning'
        case 'secondary':
          return 'badge__secondary'
        default:
          return 'badge__secondary'
      }
    },
  },
}
</script>

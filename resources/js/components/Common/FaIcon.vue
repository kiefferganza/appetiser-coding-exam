<template>
  <fa
    :icon="['fa', icon]"
    class="flex-shrink-0 h-2 w-2"
    :class="{ 'fa-spin': spin }"
  />
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      required: true
    },
    spin: {
      type: Boolean,
      default: false
    }
  }
}
</script>

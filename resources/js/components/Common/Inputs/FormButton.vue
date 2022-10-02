<template>
  <button
    v-bind="$attrs"
    class="text-white font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center"
    v-on="$listeners"
  >
    <slot v-if="!busy" />
    <fa-icon v-else class="h-6 w-6 text-white mx-auto" icon="spinner" spin />
  </button>
</template>

<script>
import FaIcon from '../FaIcon'
export default {
  components: { FaIcon },
  props: {
    busy: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

<template>
  <button
    v-bind="$attrs"
    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
    v-on="$listeners"
  >
    <span class="sr-only">{{ altText }}</span>
    <!-- Heroicon name: outline/x -->
    <svg
      class="h-6 w-6 text-white"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
      aria-hidden="true"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M6 18L18 6M6 6l12 12"
      />
    </svg>
  </button>
</template>

<script>
import LayoutButtonsMixin from '~/mixins/LayoutButtonsMixin'

export default {
  mixins: [LayoutButtonsMixin],
}
</script>

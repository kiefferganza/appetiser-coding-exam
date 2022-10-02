<template>
  <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" -->
  <span
    :class="
      selected
        ? 'border-blue-500 text-blue-600'
        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
    "
    class="cursor-pointer group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm"
    @click="$emit('click')"
  >
    <!--
        Heroicon name: solid/user

        Current: "text-indigo-500", Default: "text-gray-400 group-hover:text-gray-500"
      -->
    <slot name="content" />
  </span>
</template>
<script>
export default {
  props: {
    selected: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

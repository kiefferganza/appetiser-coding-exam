<template>
  <div class="text-center py-4">
    <common-fa-icon class="mx-auto h-12 w-12 text-gray-400" :icon="icon" />
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ title }}</h3>
    <p class="mt-1 text-sm text-gray-500">
      {{ description }}
    </p>
    <div class="mt-4">
      <slot name="action" />
    </div>
  </div>
</template>

<script>
import CommonFaIcon from '~/components/Common/FaIcon'
export default {
  components: {
    CommonFaIcon
  },
  props: {
    title: {
      type: String,
      default: 'No items here',
    },
    description: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: 'folder-open',
    },
  },
}
</script>

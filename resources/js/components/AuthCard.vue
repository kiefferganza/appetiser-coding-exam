<template>
  <div class="p-4 w-full max-w-sm bg-white rounded-lg border border-gray-200 shadow-md sm:p-6 md:p-8">
    <h5 class="text-xl font-medium text-gray-900 dark:text-white pb-3">
      {{ title }}
    </h5>
    <slot />
  </div>
</template>

<script>
export default {
  name: 'AuthCard',
  props: {
    title: { type: String, default: '' }
  }
}
</script>

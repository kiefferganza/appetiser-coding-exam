<template>
  <div>
    <label
      :for="labelFor"
      class="block mb-2 font-medium text-ventamo-navy-blue"
    >
      <span v-if="required" class="text-red-500">*</span>
      {{ label }}
    </label>
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    labelFor: {
      type: String,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
  },
}
</script>

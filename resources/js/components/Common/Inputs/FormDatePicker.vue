<template>
  <client-only>
    <date-picker
      input-class="form-input form-input__primary w-full"
      calendar-class="rounded-md mt-2 font-medium"
      typeable
      :format="format"
      v-bind="$attrs"
      v-on="$listeners"
    />
  </client-only>
</template>

<script>
export default {
  props: {
    block: {
      type: Boolean,
      default: false,
    },
    format: {
      type: String,
      default: 'yyyy-MM-dd',
    },
  },
}
</script>

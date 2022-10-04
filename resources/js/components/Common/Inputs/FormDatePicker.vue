<template>
  <Datepicker
    input-class="form-input form-input__primary w-full"
    calendar-class="rounded-md mt-2 font-medium"
    typeable
    v-bind="$attrs"
    :format="format"
    :placeholder="placeholder"
    value-type="format"
    v-on="$listeners"
  />
</template>

<script>
import Datepicker from 'vuejs-datepicker'
export default {
  components: {
    Datepicker
  },
  props: {
    block: {
      type: Boolean,
      default: false
    },
    format: {
      type: String,
      default: 'yyyy-MM-dd'
    },
    placeholder: {
      type: String,
      default: ''
    }
  }
}
</script>

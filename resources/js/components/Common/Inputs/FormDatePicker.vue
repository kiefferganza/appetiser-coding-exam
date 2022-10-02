<template>
  <Datepicker
    input-class="form-input form-input__primary w-full"
    calendar-class="rounded-md mt-2 font-medium"
    typeable
    :format="format"
    v-bind="$attrs"
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
    }
  }
}
</script>

<template>
  <div class="flex flex-col">
    <div v-for="(field, k) in message" :key="k">
      <p v-for="error in field" :key="error" class="text-sm">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script>
import upperFirst from 'lodash/upperFirst'
export default {
  props: {
    message: {
      type: [Array, String],
      default: null
    }
  },
  methods: {
    formatMessages (message) {
      return upperFirst(message)
    }
  }
}
</script>

<style scoped></style>

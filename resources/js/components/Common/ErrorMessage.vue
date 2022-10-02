<template>
  <div class="flex flex-col">
    <ul
      v-if="Array.isArray(message)"
      :class="{ 'list-disc px-6': message.length > 1 }"
    >
      <li v-for="(item, index) in message" :key="index">
        {{ formatMessages(item) }}
      </li>
    </ul>
    <span v-else>
      {{ message }}
    </span>
  </div>
</template>

<script>
import upperFirst from 'lodash/upperFirst'
export default {
  props: {
    message: {
      type: [Array, String],
      default: null,
    },
  },
  methods: {
    formatMessages(message) {
      return upperFirst(message)
    },
  },
}
</script>

<style scoped></style>

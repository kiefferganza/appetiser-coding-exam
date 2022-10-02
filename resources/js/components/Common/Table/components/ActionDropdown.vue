<template>
  <div
    :style="!show ? 'display: none' : ''"
    class="animate__animated animate__fadeIn origin-top-left absolute right-9 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
    role="menu"
  >
    <div v-for="(item, index) in items" :key="index">
      <button
        class="whitespace-normal block px-4 font-medium py-3 w-full flex items-center text-left text-sm text-gray-700 hover:bg-gray-100"
        @click="handleCallback(item.callback)"
      >
        <slot :name="`action-button-${index}`">
          {{ item.title }}
        </slot>
      </button>
    </div>
  </div>
</template>

<script>
export default {

  expose: ['open', 'close'],
  props: {
    items: {
      type: Array,
      required: true
    },
    id: {
      type: [Number, String],
      required: true
    }
  },
  data () {
    return {
      show: false
    }
  },
  methods: {
    handleCallback (fn) {
      // TODO: Add handler for router push prop
      fn(this.id)
    },
    open () {
      this.show = true
    },
    close () {
      this.show = false
    }
  }
}
</script>

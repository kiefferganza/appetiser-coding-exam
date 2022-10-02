<template>
  <div class="card">
    <div class="card-header text-white" :class="badgeVariant">
      {{ badgeTitle }}
    </div>

    <div class="card-body">
      <slot name="card-body" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    status: { type: String, default: null }
  },
  computed: {
    badgeTitle () {
      return this.status ? 'Completed' : 'Pending'
    },
    badgeVariant () {
      return this.status ? 'bg-success' : 'bg-info'
    }
  }
}
</script>

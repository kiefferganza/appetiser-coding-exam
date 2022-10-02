<template>
  <div class="card">
    <div v-if="title" class="card-header">
      {{ title }}
    </div>

    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null },
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

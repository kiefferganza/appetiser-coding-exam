<template>
  <div class="row">
    <div class="col-lg-10 m-auto">
      <card :title="$t('home')">
        Tester
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  middleware: 'auth',

  async asyncData () {
    const { data: todos } = await axios.get('/api/todos')

    return {
      todos
    }
  },

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

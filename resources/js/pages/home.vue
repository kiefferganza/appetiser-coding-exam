<template>
  <div class="grid grid-cols-4 gap-4">
    <card v-for="(todos, index) in todo.data" :key="index" :status="todos.date_completed">
      <template #card-body>
        <h5 class="card-title">
          {{ todos.title }}
        </h5>
        <p class="card-text">
          {{ todos.description }}
        </p>
        <a href="#" class="bg-blue-500">View Task</a>
      </template>
    </card>
  </div>
</template>

<script>
import axios from 'axios'
import Card from '../components/Card'
export default {
  components: {
    Card
  },
  middleware: 'auth',
  async asyncData () {
    const { data: todo } = await axios.get('/api/todos')

    return {
      todo
    }
  },
  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

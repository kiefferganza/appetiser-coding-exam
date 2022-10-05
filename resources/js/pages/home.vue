<template>
  <common-wrapper>
    <template #content>
      <Todo ref="todo" @submit-todo-details-request="submit" />
    </template>
  </common-wrapper>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import CommonWrapper from '../components/Common/Wrapper'
import Todo from '~/components/Todo'
export default {
  components: {
    CommonWrapper,
    Todo
  },
  middleware: 'auth',
  computed: {
    ...mapState('todos', ['todoCreate'])
  },
  mounted () {
    this.fetchTodos()
  },
  methods: {
    ...mapActions('todos', ['fetchTodos', 'createTodo']),

    async submit (data) {
      await this.createTodo(data)
      if (!this.todoCreate.error) {
        this.$refs.todo.hideTodoModal()
        this.$toast.success('Success')
      }
    },
    showModal () {
      this.$emit('show-user-modal')
    }
  },
  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>

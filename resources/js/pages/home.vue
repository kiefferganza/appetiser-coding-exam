<template>
  <common-wrapper>
    <template #content>
      <Todo ref="todo" @submit-todo-details-request="submit" @submit-complete-task-request="completeTaskStatus"/>
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
    ...mapActions('todos', ['fetchTodos', 'createTodo', 'updateTodo', 'completeTask']),

    async submit (data) {
      if (!data.isUpdate) {
        await this.createTodo(data)
      } else {
        await this.updateTodo(data)
      }
      if (!this.todoCreate.error) {
        this.$refs.todo.hideTodoModal()
        this.$toast.success('Success')
      }
    },
    async completeTaskStatus (data) {
      await this.completeTask(data)
      if (!this.todoCreate.error) {
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

<template>
  <common-wrapper>
    <template #content>
      <Todo ref="todo" @submit-todo-details-request="submit" @submit-update-task="updateTask" />
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
    ...mapActions('todos', ['fetchTodos', 'createTodo', 'updateTodo', 'completeTask', 'deleteTask', 'uploadFile']),

    async submit (data) {
      await this.uploadFile(data)

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
    async updateTask (data) {
      if (data.type === 'complete-task') {
        await this.completeTask(data)
      } else {
        await this.deleteTask(data)
      }
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

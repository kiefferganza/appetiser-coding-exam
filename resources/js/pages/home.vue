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
    this.fetchTags()
  },
  methods: {
    ...mapActions('todos', ['fetchTodos', 'fetchTags', 'createTodo', 'updateTodo', 'completeTask', 'archiveTask', 'deleteTask', 'uploadFile']),

    async submit (data) {
      if (!data.isUpdate) {
        await this.createTodo(data)
      } else {
        await this.updateTodo(data)
      }
      await this.uploadFile(data)
      if (!this.todoCreate.error) {
        this.$refs.todo.hideTodoModal()
        this.$toast.success('Success')
      }
    },
    async updateTask (data) {
      if (data.type === 'complete-task') {
        await this.completeTask(data)
      } else if (data.type === 'archive-task') {
        await this.archiveTask(data)
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

<template>
  <div class="flex flex-col border border-gray-100">
    <div class="inline-block min-w-full align-middle dark:bg-gray-800">
      <todo-card @show-todos-modal="showTodoModal" @complete-task="completeTask" />
    </div>
    <todo-modal
      ref="todoModal"
      :show="todoModal"
      :update-data="updateData"
      @submit="handleSubmit"
      @close-user-modal="todoModal = false"
    />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import ModalMixin from '~/mixins/ModalMixin'
import TodoCard from '~/components/Todo/components/TodoCard'
import TodoModal from '~/components/Todo/components/TodoModal'

export default {
  components: {
    TodoModal,
    TodoCard
  },
  mixins: [ModalMixin],
  data () {
    return {
      tabIndex: 0,
      todoModal: false,
      updateID: null,
      updateData: {}
    }
  },
  computed: {
    ...mapState('todos', ['todos'])
  },
  methods: {
    handleSubmit (payload) {
      this.$emit('submit-todo-details-request', {
        ...payload
      })
    },
    completeTask (payload) {
      this.$emit('submit-complete-task-request', {
        ...payload
      })
    },
    showTodoModal (data) {
      this.updateData = data
        ? this.todos.list.find(({ id }) => id === data)
        : {}
      this.todoModal = true
    },
    hideTodoModal (payload) {
      this.todoModal = false
    }
  }
}
</script>

<template>
  <div class="flex flex-col border border-gray-100">
    <div class="inline-block min-w-full align-middle dark:bg-gray-800">
      <todo-card @show-todos-modal="showTodoModal" @update-task="updateTask" />
    </div>
    <todo-modal
      ref="todoModal"
      :show="todoModal"
      :update-data="updateData"
      :is-reject="isReject"
      @submit="handleSubmit"
      @update-task="updateTask"
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
      updateData: {},
      isReject: false
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
    updateTask (payload) {
      this.$emit('submit-update-task', {
        ...payload
      })
    },
    showTodoModal (data) {
      this.updateData = data
        ? this.todos.list.find(({ id }) => id === data.id)
        : {}
      this.todoModal = true
      this.isReject = data.isReject
    },
    hideTodoModal (payload) {
      this.todoModal = false
    }
  }
}
</script>
